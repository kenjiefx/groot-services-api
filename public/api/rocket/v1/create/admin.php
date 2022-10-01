<?php

declare(strict_types=1);

use \JWT\Token;
use \Tools\TypeOf;
use User\Models\User;
use Core\HTTP\Request;
use Core\HTTP\Response;
use Tenant\Models\Tenant;
use \Tools\RequireEndpoint;
use Tenant\Models\Requester;
use Tenant\Controllers\TenantController;
use Core\Exceptions\RecordNotFoundException;
use Core\Exceptions\RocketExceptionsInterface;
use Core\Exceptions\UnauthorizedAccessException;
use Core\Exceptions\ResourceAccessForbiddenException;

require $_SERVER['DOCUMENT_ROOT'].'/imports.php';
$request = new Request;
$response = new Response;

try {

    RequireEndpoint::header();
    RequireEndpoint::method('POST');
    RequireEndpoint::payload(['token','publicKey','firstName','lastName','email','username','password']);


    $User = new User();
    echo $User->id();


    Response::transmit([
        'payload' => [
            'status'=>'200 OK',
            'message' => 'Tenant successfully updated'
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