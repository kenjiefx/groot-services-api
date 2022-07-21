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
use \tasksvc\TaskModel;
use \tasksvc\TodoModel;
use \tasksvc\TaskDB;

require $_SERVER['DOCUMENT_ROOT'].'/imports.php';
$request = new Request;
$response = new Response;


try {

    # Require headers
    RequireApiEndpoint::header();

    # Require API Method endpoint
    RequireApiEndpoint::method('PUT');

    # Require API query parameters
    RequireApiEndpoint::payload(['token','id','comment']);

    # Requester validation
    $jwt = new Token($request->payload()->token);

    if (!$jwt->isValid()) {
        throw new UnauthorizedAccessException(
            'Token provided is either expired or invalid'
        );
    }

    $taskId = Typeof::all('Task Id',$request->payload()->id,'NOT EMPTY');
    $comment = Typeof::all('Rejection comment',$request->payload()->comment,'NOT EMPTY');

    $task = TaskDB::getById($taskId);
    $task['rejection'] = [
        'createdAt' => TimeStamp::now(),
        'comment' => $comment
    ];

    $oldStatus = $task['status'];
    if ($task['status']==='rejected') {
        Response::transmit([
            'payload' => [
                'status'=>'200 OK',
                'message' => 'Task has been already rejected'
            ]
        ]);
        exit();
    }

    $task['status'] = 'rejected';

    $updatedTask = TaskModel::import($task);

    TaskDB::save($updatedTask,$oldStatus,$updatedTask->getStatus());

    Response::transmit([
        'payload' => [
            'status'=>'200 OK',
            'message' => 'Task is successfully rejected'
        ]
    ]);

    //var_dump($updatedTask);



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
        'exception' => 'Unhandled Exception'

        # Allows you to see the exact error message passed on the throw statement
        //'exception'=>$e->getMessage()
    ]);
}
