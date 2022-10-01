<?php

declare(strict_types=1);

use \JWT\Token;
use \Tools\TypeOf;
use Core\HTTP\Request;
use Core\HTTP\Response;
use Tenant\Models\Tenant;
use \Tools\RequireEndpoint;
use Tenant\Models\Requester;
use Tenant\Controllers\TenantController;
use Tenant\Controllers\Address\AddressSchema;
use Core\Exceptions\RocketExceptionsInterface;
use Core\Exceptions\UnauthorizedAccessException;
use Core\Exceptions\RecordNotFoundException;
use Core\Exceptions\ResourceAccessForbiddenException;


require $_SERVER['DOCUMENT_ROOT'].'/imports.php';
$request = new Request;
$response = new Response;

try {

    RequireEndpoint::header();
    RequireEndpoint::method('PATCH');
    RequireEndpoint::payload(['token','publicKey','key','value']);

    $key       = TypeOf::alpha('Address field key',$request->payload()->key);
    $value     = TypeOf::alphanumWithspace('Address field value',$request->payload()->value);
    $publicKey = TypeOf::alphanum('Public Key',$request->payload()->publicKey);

    # Validating valid keys and values
    $schema = new AddressSchema();
    if (!$schema::isReadonly($key)) {
        throw new ResourceAccessForbiddenException($key.' cannot be modified');
        exit();
    }

    # Getting payload from the JWT Token
    $tokenPayload = (new Token($request->payload()->token))->payload();

    # Getting the Requester Payload Object
    $Requester  = new Requester($tokenPayload);
    $Tenant     = (new Tenant())->publicKey($publicKey);
    $Controller = new TenantController($Tenant);

    $Tenant = $Controller->get();
    if (null===$Tenant) {
        throw new RecordNotFoundException('Tenant not found');
        exit();
    }

    $Tenant->business()->address()->$key($value);
    $Controller->update();

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
