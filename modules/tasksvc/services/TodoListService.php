<?php

declare(strict_types=1);
namespace tasksvc\services;
use \tasksvc\models\TodoModel;
use \glyphic\TypeOf;
use \glyphic\TimeStamp;
use \glyphic\UniqueId;
use \core\exceptions\BadRequestException;
use \core\exceptions\RecordNotFoundException;

class TodoListService {

    private array $todos = [];

    public function __construct(
        array $todos
        )
    {
        foreach ($todos as $todo) {
            if (is_object($todo)) $todo = $this->toArray($todo);
            $todoModel = new TodoModel();
            $todoModel->setAbout($todo['about'] ?? '');
            $todoModel->setCreatedAt($todo['createdAt'] ?? TimeStamp::now());
            $todoModel->setUpdatedAt($todo['updatedAt'] ?? TimeStamp::now());
            $todoModel->setStatus($todo['status'] ?? 'pending');
            $todoModel->setId($todo['id'] ?? null);
            $this->todos[$todoModel->getid()] = $todoModel;
        }
    }

    private function toArray(
        object $todo
        )
    {
        return [
            'about' => $todo->about,
            'createdAt' => $todo->createdAt,
            'updatedAt' => $todo->updatedAt,
            'status' => $todo->status,
            'id' => $todo->id
        ];
    }

    public function complete(
        string $todoId
        )
    {
        $todoFound = false;
        foreach ($this->todos as $todo) {
            if ($todo->getId()===$todoId) {
                $todoFound = true;
                $todo->complete();
            }
        }
        if (!$todoFound) {
            throw new RecordNotFoundException(
                'Todo Id '.$todoId.' not found'
            );
        }
    }

    public function uncomplete(
        string $todoId
        )
    {
        $this->setTodoStatus($todoId,'uncomplete');
    }

    private function setTodoStatus(
        string $todoId,
        string $status
        )
    {
        $todoFound = false;
        foreach ($this->todos as $todo) {
            if ($todo->getId()===$todoId) {
                $todoFound = true;
                $todo->$status();
            }
        }
        if (!$todoFound) {
            throw new RecordNotFoundException(
                'Todo Id '.$todoId.' not found'
            );
        }
    }

    public function isAllComplete()
    {
        $allComplete = true;
        foreach ($this->todos as $todo) {
            if (!$todo->isComplete()) {
                $allComplete = false;
            }
        }
        return $allComplete;
    }

    public function export()
    {
        $exportable = [];
        foreach ($this->todos as $todo) {
            $exportable[$todo->getid()] = $todo->export();
        }
        return $exportable;
    }

    public function count()
    {
        return count($this->todos);
    }

}
