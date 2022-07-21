<?php

declare(strict_types=1);
namespace tasksvc;
use \glyphic\TypeOf;
use \glyphic\TimeStamp;
use \glyphic\UniqueId;

class TodoModel {

    public string $about = '';
    private string $createdAt = '';
    private string $updatedAt = '';
    private string $status = 'pending';
    private string|null $id = null;

    public function __construct(
        string|null $id = null,
        string|null $createdAt = null,
        string|null $about = null,
        string|null $status = null,
        string|null $updatedAt = null
        )
    {
        $this->id = $id ?? UniqueId::create32bitKey(UniqueId::BETANUMERIC);
        $this->createdAt = $createdAt ?? TimeStamp::now();
        $this->updatedAt = $updatedAt ?? TimeStamp::now();
        $this->about = $about ?? 'No data';
        $this->status = $status ?? 'pending';

    }

    public function setAbout(
        string $about
        )
    {
        $this->about = TypeOf::all('Todo about',$about,'NOT EMPTY');
        $this->updatedAt = TimeStamp::now();
    }

    public function complete()
    {
        $this->status = 'completed';
    }

    public function uncomplete()
    {
        $this->status = 'pending';
    }

    public function setStatus(
        string $status
        )
    {
        $this->status = $status;
        $this->updatedAt = TimeStamp::now();
    }

    public function getId()
    {
        return $this->id;
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

}
