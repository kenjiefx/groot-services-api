<?php

declare(strict_types=1);

use Core\HTTP\Request;
use Core\HTTP\Response;
use Core\Exceptions\RocketExceptionsInterface;
use Core\Exceptions\UnauthorizedAccessException;
use \Tools\RequireEndpoint;
use \Tools\TypeOf;
use \JWT\Token;
use Tenant\Models\Requester;


require $_SERVER['DOCUMENT_ROOT'].'/imports.php';
$request = new Request;
$response = new Response;

try {

    RequireEndpoint::header();
    RequireEndpoint::method('POST');
    RequireEndpoint::payload([
        'publicKey',
        'secretKey',
        'grantType=credentials'
    ]);

    if (getenv('GLYPHIC_PUBLIC')!==TypeOf::alphanum(
        'Glyphic Public Key',
        $request->payload()->publicKey
    )) {
        throw new UnauthorizedAccessException(
            'Invalid Public Key'
        );
    }

    if (getenv('GLYPHIC_SECRET')!==TypeOf::alphanum(
        'Glyphic Secret Key',
        $request->payload()->secretKey
    )) {
        throw new UnauthorizedAccessException(
            'Invalid Secret Key'
        );
    }

    $requester = new Requester([
        'userId' => 'default',
        'tenantId' => 'default',
        'userStatus' => 'default',
        'permissions' => []
    ]);

    $token = new Token();
    $token->payload($requester->export());
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

    if ($e instanceof RocketExceptionsInterface) {

        Response::transmit([
            'code' => $e->code(),
            # Provides only generic error message
            # 'exception' => 'RocketExceptionsInterface::'.$e->exception(),
            # Allows you to see the exact error message passed on the throw statement
            'exception'=>$e->getMessage()
        ]);

        exit();

    }
    Response::transmit([
        'code' => 400,
        'exception' => 'Unhandled Exception'
    ]);
}
