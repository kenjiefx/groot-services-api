<?php

declare(strict_types=1);
namespace tasksvc\models;
use \core\exceptions\ResourceAccessForbiddenException;
use \glyphic\TypeOf;
use \glyphic\TimeStamp;
use \glyphic\UniqueId;

class TodoModel {

    public string $about;
    private string $createdAt;
    private string $updatedAt;
    private string $status;
    private string $id;

    public function setAbout(
        string $about
        )
    {
        $this->about = TypeOf::all('Todo about',$about,'NOT EMPTY');
    }

    public function setCreatedAt(
        string $createdAt
        )
    {
        $this->createdAt = $createdAt;
    }

    public function isComplete()
    {
        return ($this->status==='completed');
    }

    public function complete()
    {
        if ($this->status==='completed') {
            throw new ResourceAccessForbiddenException(
                'Todo item has already been completed'
            );
        }
        $this->status = 'completed';
        $this->updatedAt = TimeStamp::now();
    }

    public function uncomplete()
    {
        if ($this->status==='pending') {
            throw new ResourceAccessForbiddenException(
                'Todo item is already un-completed'
            );
        }
        $this->status = 'pending';
        $this->updatedAt = TimeStamp::now();
    }

    public function setUpdatedAt(
        string $updatedAt
        )
    {
        $this->updatedAt = $updatedAt;
    }

    public function setStatus(
        string $status
        )
    {
        $this->status = $status;
    }

    public function setId(
        string|null $id
        )
    {
        $this->id = $id ?? UniqueId::create32bitKey(UniqueId::BETANUMERIC);
    }

    public function export()
    {
        return [
            'id' => $this->id,
            'about' => $this->about,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'status' => $this->status
        ];
    }


    public function getId()
    {
        return $this->id;
    }

}
