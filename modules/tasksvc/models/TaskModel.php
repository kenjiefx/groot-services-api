<?php

declare(strict_types=1);
namespace tasksvc\models;
use tasksvc\models\UserModel;
use tasksvc\models\TaskType;
use tasksvc\services\TodoListService;
use tasksvc\services\StatusManager;
use tasksvc\services\Moderator;
use \glyphic\TypeOf;
use \glyphic\TimeStamp;
use \glyphic\UniqueId;

class TaskModel {

    private string $id;
    private UserModel $User;
    private TodoListService $TodoList;
    private string $description;
    private TaskType $Type;
    private bool $terms;
    private string $createdAt;
    private string $updatedAt;
    private StatusManager $Status;
    private Moderator $Moderator;

    public function setId(
        string $id
        )
    {
        $this->id = TypeOf::all('Task Id',$id,'NOT EMPTY');
    }

    public function setUser(
        UserModel $user
        )
    {
        $this->User = $user;
    }

    public function setTodoList(
        TodoListService $todoList
        )
    {
        $this->TodoList = $todoList;
    }

    public function setCreatedAt(
        string $createdAt
        )
    {
        $this->createdAt = $createdAt;
    }

    public function setStatus(
        StatusManager $status
        )
    {
        $this->Status = $status;
    }

    public function setDescription(
        string $description
        )
    {
        $this->description = $description;
    }

    public function setType(
        TaskType $type
        )
    {
        $this->Type = $type;
    }

    public function setUpdatedAt (
        string $updatedAt
        )
    {
        $this->updatedAt = $updatedAt;
    }

    public function setModerator(
        Moderator $moderator
        )
    {
        $this->Moderator = $moderator;
    }




    public function export()
    {
        return [
            'id' => $this->id,
            'user' => $this->User->export(),
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'todos' => $this->TodoList->export(),
            'status' => $this->Status->export(),
            'description' => $this->description,
            'type' => $this->Type->get(),
            'moderation' => $this->Moderator->export()
        ];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTodoList()
    {
        return $this->TodoList;
    }

    public function getStatus()
    {
        return $this->Status;
    }

    public function getUser()
    {
        return $this->User;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function countTodos()
    {
        return $this->TodoList->count();
    }

    public function getType()
    {
        return $this->Type->get();
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getModerator()
    {
        return $this->Moderator;
    }



}
