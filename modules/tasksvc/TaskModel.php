<?php

declare(strict_types=1);
namespace tasksvc;
use \tasksvc\TodoModel;
use \glyphic\TypeOf;
use \glyphic\TimeStamp;
use \glyphic\UniqueId;
use \core\exceptions\BadRequestException;

class TaskModel {

    private string $email = '';
    private array $todos = [];
    private string $description = '';
    private string $type = '';
    private bool $terms = false;
    private string $createdAt = '';
    private string $updatedAt = '';
    private string $status = 'new';
    private string|null $id = null;
    private array $rejection = [];

    public function __construct(
        string|null $id = null,
        string|null $createdAt = null
        )
    {
        $this->createdAt = $createdAt ?? TimeStamp::now();
        $this->id = $id ?? 'TASK-'.time().'-'.UniqueId::createShortKey(UniqueId::BETANUMERIC);
        //$this->id = $id ?? (time().rand(100,999));
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function complete()
    {
        $this->status = 'completed';
    }

    public function uncomplete()
    {
        $this->status = 'pending';
    }

    public function reject()
    {
        $this->status = 'rejected';
    }

    public function setRejection(
        array $rejection
        )
    {
        $this->rejection = $rejection;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setEmail(
        string $email
        )
    {
        $this->email = trim(TypeOf::email('Task User Email',$email));
        $this->updatedAt = TimeStamp::now();
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setDescription(
        string|null $description
        )
    {
        $this->description = TypeOf::all('Task Description',$description);
        $this->updatedAt = TimeStamp::now();
    }

    public function setType(
        string $type
        )
    {
        $this->type = TypeOf::all('Task type',$type,'NOT EMPTY');
        $this->updatedAt = TimeStamp::now();
    }

    public function addTodo(
        TodoModel $todo
        )
    {
        $this->todos[$todo->getId()] = $todo->export();
        $this->updatedAt = TimeStamp::now();
    }

    public function setTerms(
        bool $terms
        )
    {
        $this->terms = $terms;
        $this->updatedAt = TimeStamp::now();
    }

    public function toJson()
    {
        $export = [
            'id' => $this->id,
            'status' => $this->status,
            'updatedAt' => $this->updatedAt,
            'createdAt' => $this->createdAt,
            'terms' => $this->terms,
            'type' => $this->type,
            'todos' => $this->todos,
            'description' => $this->description,
            'email' => $this->email,
            'rejection' => $this->rejection
        ];
        return json_encode($export);
    }

    public static function import(
        array $task
        )
    {
        $imported = new TaskModel($task['id'],$task['createdAt']);
        $imported->setEmail($task['email']);
        $imported->setDescription($task['description']);
        $imported->setType($task['type']);
        $imported->setTerms($task['terms']);

        # Checking whether the task is complete
        $todoCount = count($task['todos']);
        $completedCount = 0;

        if (isset($task['rejection'])) {
            $imported->setRejection($task['rejection']);
        }

        foreach ($task['todos'] as $todoItem) {
            $todo = new TodoModel($todoItem['id'],$todoItem['createdAt'],$todoItem['about'],$todoItem['status'],$todoItem['updatedAt']);
            $imported->addTodo($todo);
            if ($todoItem['status']==='completed') $completedCount++;
        }

        if ($todoCount===$completedCount) {
            $imported->complete();
        } else {
            if ($task['status']!=='rejected') {
                $imported->uncomplete();
            } else {
                $imported->reject();
            }
        }

        return $imported;
    }





}
