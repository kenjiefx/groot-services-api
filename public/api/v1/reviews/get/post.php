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
        "get post data" => "
            SELECT p.postId, p.postTitle, p.postBody, p.createdAt, p.visibility, p.userId, u.username, i.src,
            f.firstName, f.lastName, f.profilePhoto,
                (SELECT COUNT(reviewId) FROM m_glyf_reviews
                     WHERE reviewFor = :postId
                     AND status = 'ACTIVE'
                ) as totalReviewCount,
                (SELECT SUM(score) FROM m_glyf_reviews
                     WHERE reviewFor = :postId
                     AND status = 'ACTIVE'
                ) as totalReviewScore,
                (SELECT reviewerId FROM m_glyf_reviews
                     WHERE reviewerId = :userId
                     AND reviewFor = :postId
                     AND status = 'ACTIVE'
                     LIMIT 1
                ) as hasRequesterReviewed
            FROM m_glyf_posts p
            LEFT JOIN s_glyf_post_img i ON i.postId = p.postId
            LEFT JOIN m_glyf_user u ON u.userId = p.userId
            LEFT JOIN s_glyf_profile f ON f.userId = p.userId
            WHERE p.postId = :postId
            AND p.status = 'ACTIVE'
            AND p.tenantId IN (SELECT tenantId FROM m_glyf_tnt WHERE publicKey = :publicKey)
        ",
        "get post reviews" => "
            SELECT r.reviewId, r.reviewerId, r.title, r.content, r.score, r.createdAt, u.username, p.firstName, p.lastName, p.profilePhoto
            FROM m_glyf_reviews r
            LEFT OUTER JOIN m_glyf_user u ON r.reviewerId = u.userId
                AND u.recordType = 'tenant.user'
            LEFT OUTER JOIN s_glyf_profile p ON r.reviewerId = p.userId
                AND p.recordType = 'tenant.user'
            WHERE r.reviewFor = :postId
            AND r.status = 'ACTIVE'
            AND r.tenantId IN (SELECT tenantId FROM m_glyf_tnt WHERE publicKey = :publicKey)
        ",
        "get user reviews bottomline" => "
            SELECT COUNT(reviewFor) as totalReviews, SUM(score) as totalScore
            FROM m_glyf_reviews r
            WHERE r.reviewFor IN (SELECT postId FROM m_glyf_posts WHERE userId = :userId)
            AND r.status = 'ACTIVE'
        "
    ];

    # Require headers
    RequireApiEndpoint::header();

    # Require API Method endpoint
    RequireApiEndpoint::method('GET');

    # Require API query parameters
    RequireApiEndpoint::query([
        'id',
        'publickey'
    ]);

    $requester['userId'] = 'nonexistent';
    $requester['type'] = 'public';

    if (isset($request->query()->token)) {

        if ($request->query()->token!=='public') {

            $requester['type'] = 'user';

            # Requester validation
            $jwt = new Token($request->query()->token);

            if (!$jwt->isValid()) {
                throw new UnauthorizedAccessException(
                    'Token provided is either expired or invalid'
                );
            }

            $payload = $jwt->payload();

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

        }

    }

    /**
     * ===================== QUERY =======================
     * Get post data according to the post ID
     * NOTE: UserID is passed as one of the parameters,
     * but it is only used to determine whether the
     * requester has reviewed this post or not
     * ===================================================
     */
    $query = new PDOQueryController(
        $queries['get post data']
    );

    $query->prepare([
        ':postId' => TypeOf::alphanum(
            'Post Id',
            $request->query()->id
        ),
        ':publicKey'=>TypeOf::alphanum(
            'Public Key',
            $request->query()->publickey
        ),
        'userId' => TypeOf::alphanum(
            'Requester User Id',
            $requester['userId']
        )
    ]);

    $post = $query->get();

    if (!$post['hasRecord']) {
        throw new RecordNotFoundException(
            'Post not found'
        );
    }

    if ($requester['type']==='public') {

        if ($post['visibility']<50) {
            throw new RecordNotFoundException(
                'Post is not public'
            );
        }

    }

    if ($requester['type']!=='public') {
        if ($post['visibility']<50) {
            if ($post['userId']!==$requester['userId']) {
                throw new RecordNotFoundException(
                    'Post is not public'
                );
            }
        }
    }

    /**
     * ================== CHECKPOINT ===================
     * Tells whether the owner of this post is the same
     * user as with the one who requested to get this post
     * data
     * =================================================
     */
    $ownsThisPost = false;
    if ($post['userId']===$requester['userId']) {
        $ownsThisPost = true;
    }

    $query = new PDOQueryController(
        $queries['get user reviews bottomline']
    );
    $query->prepare([
        ':userId' => $post['userId']
    ]);
    $userBottomline = $query->get();

    $userAverageScore = 0;
    if ($userBottomline['totalScore']>0) {
        $userAverageScore = $userBottomline['totalScore'] / $userBottomline['totalReviews'];
    }

    $query = new PDOQueryController(
        $queries['get post reviews']
    );

    $query->prepare([
        ':postId' => $request->query()->id,
        ':publicKey' => $request->query()->publickey,
    ]);

    $reviews = $query->getAll();

    Response::transmit([
        'payload' => [
            'id' => $post['postId'],
            'title' =>$post['postTitle'],
            'body' => $post['postBody'],
            'src' => $post['src'],
            'createdAt' => $post['createdAt'],
            'user' => [
                'username' => $post['username'],
                'firstName' => $post['firstName'],
                'lastName' => $post['lastName'],
                'profilePhoto' => $post['profilePhoto'],
                'bottomLine' => [
                    'totalReviews' => $userBottomline['totalReviews'],
                    'averageScore' => $userAverageScore
                ]
            ],
            'bottomLine' => [
                'totalReviews' => $post['totalReviewCount'],
                'totalReviewScore' => $post['totalReviewScore']
            ],
            'reviews' => $reviews,
            'requester' => [
                'type' => $requester['type'],
                'hasReviewed' => $post['hasRequesterReviewed'],
                'ownsThisPost' => $ownsThisPost
            ]
        ]
    ]);

} catch (\Exception $e) {
    if ($e instanceof \core\exceptions\RocketExceptionsInterface) {
        Response::transmit([
            'code' => $e->code(),

            # Provides only generic error message
            //'exception' => 'RocketExceptionsInterface::'.$e->exception(),

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
