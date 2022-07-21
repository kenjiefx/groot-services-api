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
    RequireApiEndpoint::method('POST');

    # Require API query parameters
    RequireApiEndpoint::query(['token']);

    # Require API payload
    RequireApiEndpoint::payload([
        'email',
        'todos',
        'description',
        'type',
        'hasAcceptedTerms'
    ]);



    # Requester validation
    $jwt = new Token($request->query()->token);

    if (!$jwt->isValid()) {
        throw new UnauthorizedAccessException(
            'Token provided is either expired or invalid'
        );
    }

    $task = new TaskModel();
    $task->setEmail($request->payload()->email);
    $task->setDescription($request->payload()->description);
    $task->setType($request->payload()->type);
    $task->setTerms($request->payload()->hasAcceptedTerms);

    if (!is_array($request->payload()->todos)) {
        throw new BadRequestException(
            'Todo list must be type of array'
        );
    }

    foreach ($request->payload()->todos as $todoItem) {

        $todo = new TodoModel();

        if (!isset($todoItem->about)) {
            throw new BadRequestException(
                'Todo item requires about field'
            );
        }

        $todo->setAbout($todoItem->about);

        $task->addTodo($todo);

    }

    TaskDB::save($task,'new','new');

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
