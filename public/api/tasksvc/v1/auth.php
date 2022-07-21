<?php

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
use \glyphic\TimeStamp;
use \glyphic\TypeOf;
use \glyphic\UniqueId;

require $_SERVER['DOCUMENT_ROOT'].'/imports.php';
$request = new Request;
$response = new Response;

try {

    # Require headers
    RequireApiEndpoint::header();

    # Require API Method endpoint
    RequireApiEndpoint::method('POST');

    # Require API payload
    RequireApiEndpoint::payload([
        'publicKey',
        'secretKey',
        'grantType=credentials'
    ]);

    if (getenv('TASK_PUBLIC')!==TypeOf::alphanum(
        'Glyphic Public Key',
        $request->payload()->publicKey
    )) {
        throw new UnauthorizedAccessException(
            'Invalid Public Key'
        );
    }

    if (getenv('TASK_SECRET')!==TypeOf::alphanum(
        'Glyphic Secret Key',
        $request->payload()->secretKey
    )) {
        throw new UnauthorizedAccessException(
            'Invalid Secret Key'
        );
    }

    $token = new Token();
    $token->payload([
        'requester'=>'root'
    ]);
    $token->create();

    Response::transmit([
        'payload' => [
            'status'=>'200 OK',
            'message' => 'authenticated',
            'token' => $token->get(),
            'exp' => '7min'
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
    ]);
}
