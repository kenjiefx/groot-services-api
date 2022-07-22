<?php

declare(strict_types=1);
namespace tasksvc\controllers;
use \core\exceptions\BadRequestException;
use \core\exceptions\ResourceAccessForbiddenException;
use \tasksvc\models\TaskModel;
use \glyphic\TimeStamp;
use \glyphic\TypeOf;
use \glyphic\UniqueId;

class TaskController {

    private TaskModel $task;

    public function __construct(
        TaskModel $task
        )
    {
        $this->task = $task;
    }

    public function acceptTask(
        string|null $comment = null
        )
    {
        $this->task->getModerator()->accept($comment);
        $this->task->setUpdatedAt(TimeStamp::now());
        $this->task->getStatus()->toPending();
    }

    public function rejectTask(
        string|null $comment = null
        )
    {
        $this->task->getModerator()->reject($comment);
        $this->task->setUpdatedAt(TimeStamp::now());
        $this->task->getStatus()->toRejected();
    }

    public function completeTodo(
        string $todoId
        )
    {
        $this->denyActionOnStatus('new');
        $this->denyActionOnStatus('rejected');
        $this->task->getTodoList()->complete($todoId);
        $this->checkTaskCompletion();
    }

    public function unCompleteTodo(
        string $todoId
        )
    {
        $this->denyActionOnStatus('new');
        $this->denyActionOnStatus('rejected');
        $this->task->getTodoList()->uncomplete($todoId);
        $this->checkTaskCompletion();
    }

    private function denyActionOnStatus(
        string $status
        )
    {
        if ($this->task->getStatus()->current()===$status) {
            throw new ResourceAccessForbiddenException(
                'Task with '.$status.' status can not complete todo item'
            );
        }
    }

    private function checkTaskCompletion()
    {
        if ($this->task->getTodoList()->isAllComplete()) {
            $this->task->setUpdatedAt(TimeStamp::now());
            $this->task->getStatus()->toCompleted();
        } else {
            $this->task->setUpdatedAt(TimeStamp::now());
            $this->task->getStatus()->toPending();
        }
    }

}
