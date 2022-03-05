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
        "do user exists" => "
            SELECT u.userId
            FROM m_glyf_user u
            WHERE u.tenantId IN (SELECT tenantId FROM m_glyf_tnt WHERE publicKey = :publicKey)
            AND u.email = :email
            OR u.username = :username
        "
    ];

    # Require headers
    RequireApiEndpoint::header();

    # Require API Method endpoint
    RequireApiEndpoint::method('GET');

    # Require API query parameters
    RequireApiEndpoint::query(['username','publicKey']);


    $query = new PDOQueryController(
        $queries['do user exists']
    );
    $query->prepare([
        ':publicKey' => TypeOf::alphanum(
            'Public Key',
            $request->query()->publicKey
        ),
        ':email' => trim(TypeOf::email(
            'User Email',
            $request->query()->email ?? 'unknown@email.com')
        ),
        ':username' => trim(TypeOf::alphanum(
            'Username',
            $request->query()->username ?? 'unknown')
        )
    ]);
    $user = $query->get();

    if ($user['doExist']) {
        throw new AlreadyExistsException(
            'User already exists with the given username and/or email'
        );
    }

    Response::transmit([
        'payload' => [
            'status'=>'200 OK',
            'message' => 'your_message_here'
        ]
    ]);

} catch (\Exception $e) {
    if ($e instanceof \core\exceptions\RocketExceptionsInterface) {
        Response::transmit([
            'code' => $e->code(),

            # Provides only generic error message
            'exception' => 'RocketExceptionsInterface::'.$e->exception(),

            # Allows you to see the exact error message passed on the throw statement
            // 'exception'=>$e->getMessage()
        ]);
        exit();
    }
    Response::transmit([
        'code' => 400,
        'exception' => 'Unhandled Exception'

        # Allows you to see the exact error message passed on the throw statement
        //'exception'=>$e->getMessage()
    ]);
}
