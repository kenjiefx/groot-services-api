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
use \tasksvc\services\TaskDBService;

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
        'email',
        'todos',
        'description',
        'type',
        'hasAcceptedTerms'
    ]);

    $task = (
        new TaskFactory([
            'user' => [
                'email' => $request->payload()->email
            ],
            'todos' => $request->payload()->todos,
            'description' => $request->payload()->description,
            'type' => $request->payload()->type
        ])
    )->create();

    $taskDb = new TaskDBService($task);
    $taskDb->save();

    Response::transmit([
        'payload' => [
            'status'=>'200 OK',
            'message' => 'Task created successfully',
            'data' => $task->export()
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
