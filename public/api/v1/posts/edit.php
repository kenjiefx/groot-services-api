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
        "edit post" => "
            UPDATE m_glyf_posts
            SET postTitle = :postTitle, postBody = :postBody, updatedAt = :updatedAt
            WHERE postId = :postId
            AND userId = :userId
            AND recordType = 'tenant.user.post'
            AND tenantId IN (SELECT tenantId FROM m_glyf_tnt WHERE publicKey = :publicKey)
        "
    ];

    # Require headers
    RequireApiEndpoint::header();

    # Require API Method endpoint
    RequireApiEndpoint::method('PUT');

    # Require API payload
    RequireApiEndpoint::payload([
        'token',
        'postId',
        'postTitle',
        'postBody'
    ]);


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

    $query = new PDOQueryController(
        $queries['edit post']
    );
    $query->prepare([
        ':userId' => TypeOf::alphanum(
            'User Id',
            $requester['userId']
        ),
        ':publicKey' => TypeOf::alphanum(
            'Public Key',
            $requester['publicKey']
        ),
        ':postId' => TypeOf::alphanum(
            'Post Id',
            $request->payload()->postId
        ),
        ':postTitle' => TypeOf::all(
            'Post Title',
            $request->payload()->postTitle,
            'NULLABLE'
        ),
        ':postBody' => TypeOf::all(
            'Post Body',
            $request->payload()->postBody,
            'NOT EMPTY'
        ),
        ':updatedAt' => TimeStamp::now()
    ]);
    $query->post();



    Response::transmit([
        'payload' => [
            'status'=>'200 OK',
            'message' => 'Post modified successfully'
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
