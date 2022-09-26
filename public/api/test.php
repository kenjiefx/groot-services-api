<?php 

use Core\HTTP\Request;
use Core\HTTP\Response;
use User\Controllers\UserController;
use core\exceptions\RocketExceptionsInterface;

require $_SERVER['DOCUMENT_ROOT'].'/imports.php';

$request = new Request;
$response = new Response;

try {

    $user = new \User\Models\User();
    $user->firstName('Kenjie');
    $user->lastName('Terrado');
    $user->password('1234');
    $user->email('terrado.kenjie@gmail.com');
    $user->username('rterrado');

    $controller = new UserController($user);
    $controller->create();

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
        'exception' => $e->getMessage()
    ]);
}