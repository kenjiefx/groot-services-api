<?php

declare(strict_types=1);

use Core\HTTP\Request;
use Core\HTTP\Response;
use Core\Exceptions\RocketExceptionsInterface;


require $_SERVER['DOCUMENT_ROOT'].'/imports.php';
$request = new Request;
$response = new Response;

try {

    

} catch (\Exception $e) {

    if ($e instanceof RocketExceptionsInterface) {

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
