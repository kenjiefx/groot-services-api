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
use \tasksvc\factories\TaskFactory;
use \tasksvc\controllers\TaskController;
use \tasksvc\services\TaskDBService;

require $_SERVER['DOCUMENT_ROOT'].'/imports.php';
$request = new Request;
$response = new Response;


try {

    # Require headers
    RequireApiEndpoint::header();

    # Require API Method endpoint
    RequireApiEndpoint::method('PUT');

    # Require API query parameters
    RequireApiEndpoint::payload(['token','taskid','todoid']);

    # Requester validation
    $jwt = new Token($request->payload()->token);

    if (!$jwt->isValid()) {
        throw new UnauthorizedAccessException(
            'Token provided is either expired or invalid'
        );
    }

    $task = TaskDBService::getTask(
        'all',
        TypeOf::all('Task Id',$request->payload()->taskid,'NOT EMPTY')
    );

    $controller = new TaskController($task);
    $controller->completeTodo(
        TypeOf::alphanum('Todo Id',$request->payload()->todoid)
    );

    $taskDb = new TaskDBService($task);
    $taskDb->save();


    Response::transmit([
        'payload' => [
            'status'=>'200 OK',
            'message' => 'Todo item is updated'
        ]
    ]);


} catch (\Exception $e) {
    if ($e instanceof \core\exceptions\RocketExceptionsInterface) {
        Response::transmit([
            'code' => $e->code(),
            # Provides only generic error message
            'exception' => 'RocketExceptionsInterface::'.$e->exception(),
            # Allows you to see the exact error message passed on the throw statement
            # 'exception'=>$e->getMessage()
        ]);
        exit();
    }
    Response::transmit([
        'code' => 400,
        'exception' => 'Unhandled Exception'
        # Allows you to see the exact error message passed on the throw statement
        # 'exception'=>$e->getMessage()
    ]);
}
