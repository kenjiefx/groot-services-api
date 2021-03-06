<?php

/**
 * API Description Here
 *
 */

declare(strict_types=1);

# Common libraries
use \core\http\Request;
use \core\http\Response;
use \core\exceptions\UnauthorizedAccessException;
use \core\exceptions\BadRequestException;
use \core\exceptions\AlreadyExistsException;
use \core\exceptions\ConfigurationErrorException;
use \core\exceptions\RecordNotFoundException;
use \core\exceptions\ResourceAccessForbiddenException;
use \jwt\Token;
use \glyphic\RequireApiEndpoint;
use \glyphic\PDOQueryController;
use \glyphic\PDOTransaction;
use \glyphic\QueryBuilder;
use \glyphic\TimeStamp;
use \glyphic\TypeOf;
use \glyphic\UniqueId;

require $_SERVER['DOCUMENT_ROOT'].'/imports.php';
$request = new Request;
$response = new Response;

try {

    # Declare all your database queries here
    $queries = [
        "create new post" => "
            INSERT INTO m_glyf_posts
            (postId, userId, postTitle, visibility, postBody, createdAt, updatedAt, status, tenantId, recordType)
            VALUES
            (:postId, :userId, :postTitle, :visibility, :postBody, :createdAt, :updatedAt, 'ACTIVE', (SELECT tenantId FROM m_glyf_tnt WHERE publicKey = :publicKey), 'tenant.user.post')
        ",
        "create new post image" => "
            INSERT INTO s_glyf_post_img
            (postId, imageId, visibility, src, alt, tags, description, createdAt, updatedAt, status, tenantId, recordType)
            VALUES
            (:postId, :imageId, :visibility, :src, :alt, :tags, :description, :createdAt, :updatedAt, :status, (SELECT tenantId FROM m_glyf_tnt WHERE publicKey = :publicKey), 'tenant.user.post.image')
        "
    ];

    # Require headers
    RequireApiEndpoint::header();

    # Require API Method endpoint
    RequireApiEndpoint::method('POST');

    # Require API payload
    RequireApiEndpoint::payload([
        'token',
        'postTitle',
        'postBody'
    ]);

    # Requester validation
    $jwt = new Token($request->payload()->token);

    if (!$jwt->isValid()) {
        throw new UnauthorizedAccessException(
            'Token provided is either expired or invalid'
        );
    }

    $payload = $jwt->payload();
    $requester = [];

    # Getting the requester User Id
    $requester['userId'] = TypeOf::alphanum(
        'User Id',
        $payload['userId'] ?? null
    );

    # Getting the requester Public Key
    $requester['publicKey'] = TypeOf::alphanum(
        'Public key',
        $payload['publicKey'] ?? null
    );

    # Making sure that the user is on active status
    if ('ACTIVE'!==TypeOf::alpha(
        'User status',
        $payload['status'] ?? null
    )) {
        throw new UnauthorizedAccessException(
            'Requester status is not active'
        );
    }

    # Post Visibility
    if (isset($request->payload()->visibility)) {
        $visibility = $request->payload()->visibility;
        if ($visibility==='public') {
            $visibilityScore = 99;
        }
        if ($visibility==='private') {
            $visibilityScore = 1;
        }
    }

    $post = [
        ':postId' => UniqueId::create32bitKey(UniqueId::BETANUMERIC),
        ':userId' => TypeOf::alphanum(
            'User Id',
            $requester['userId']
        ),
        ':publicKey' => TypeOf::alphanum(
            'Public Key',
            $requester['publicKey']
        ),
        ':postTitle' => TypeOf::all(
            'Post Title',
            $request->payload()->postTitle,
            'NULLABLE'
        ),
        ':visibility' => $visibilityScore ?? 99,
        ':postBody' => TypeOf::all(
            'Post Body',
            $request->payload()->postBody,
            'NOT EMPTY'
        ),
        ':createdAt' => TimeStamp::now(),
        ':updatedAt' => TimeStamp::now()
    ];

    # Optional parameter: Post with image
    if (isset($request->payload()->photo)) {
      $image = [
          ':postId' => $post[':postId'],
          ':imageId' => UniqueId::create32bitKey(UniqueId::BETANUMERIC),
          ':visibility' => $visibilityScore,
          ':src' => TypeOf::url(
            'Post Image URL',
            $request->payload()->photo
          ),
          ':alt' => null,
          ':tags' => null,
          ':description' => null,
          ':createdAt' => TimeStamp::now(),
          ':updatedAt' => TimeStamp::now(),
          ':status' => 'ACTIVE',
          ':publicKey' => $requester['publicKey']
      ];
      $imageQuery = new PDOQueryController(
          $queries['create new post image']
      );
      $imageQuery->prepare($image);
      $imageQuery->post();
    }

    $query = new PDOQueryController(
        $queries['create new post']
    );
    $query->prepare($post);
    $query->post();

    Response::transmit([
        'code' => 201,
        'payload' => [
            'status'=>'201',
            'message' => 'Post created'
        ]
    ]);

} catch (\Exception $e) {
    if ($e instanceof \core\exceptions\RocketExceptionsInterface) {
        Response::transmit([
            'code' => $e->code(),

            # Provides only generic error message
            // 'exception' => 'RocketExceptionsInterface::'.$e->exception(),

            # Allows you to see the exact error message passed on the throw statement
            'exception'=>$e->getMessage()
        ]);
        exit();
    }
    Response::transmit([
        'code' => 400,
        // 'exception' => 'Unhandled Exception'

        # Allows you to see the exact error message passed on the throw statement
         'exception'=>$e->getMessage()
    ]);
}
