<?php

declare(strict_types=1);
namespace tasksvc\factories;
use tasksvc\models\TaskModel;
use tasksvc\models\TaskType;
use tasksvc\models\UserModel;
use tasksvc\services\TodoListService;
use tasksvc\services\StatusManager;
use tasksvc\services\Moderator;
use \glyphic\TypeOf;
use \glyphic\TimeStamp;
use \glyphic\UniqueId;
use \core\exceptions\BadRequestException;

class TaskFactory {

    public TaskModel $task;

    public function __construct(
        array $props
        )
    {

        $task = new TaskModel();

        $task->setUser(
            new UserModel($props['user']??[])
        );

        $task->setId(
            $props['id'] ?? 'TASK-'.time().'-'.UniqueId::createShortKey(UniqueId::BETANUMERIC)
        );


        $task->setTodoList(
            new TodoListService($props['todos']??[])
        );

        $task->setCreatedAt(
            $props['createdAt'] ?? TimeStamp::now()
        );

        $task->setStatus(
            new StatusManager($props['status']??[
                'previous' => 'new',
                'current' => 'new'
            ])
        );

        $task->setDescription(
            $props['description'] ?? ''
        );

        $task->setType(
            new TaskType($props['type']??null)
        );

        $task->setModerator(
            new Moderator($props['moderation']??[])
        );

        $task->setUpdatedAt(
            $props['updatedAt'] ?? TimeStamp::now()
        );


        $this->task = $task;


    }

    public function create()
    {
        return $this->task;
    }

}
