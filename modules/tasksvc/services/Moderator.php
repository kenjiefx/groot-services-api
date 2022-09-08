<?php

declare(strict_types=1);
namespace tasksvc\services;
use \core\exceptions\BadRequestException;
use \core\exceptions\ResourceAccessForbiddenException;
use \glyphic\TypeOf;
use \glyphic\TimeStamp;
use \glyphic\UniqueId;

class Moderator {

    private bool $isModerated;
    private string|null $status;
    private string $updatedAt;
    private string|null $comment;

    public function __construct(
        array $moderation
        )
    {
        $this->isModerated = $moderation['isModerated'] ?? false;
        $this->status = $moderation['status'] ?? null;
        $this->updatedAt = $moderation['updatedAt'] ?? TimeStamp::now();
        $this->comment = $moderation['comment'] ?? null;
    }

    public function accept(
        string|null $comment = null
        )
    {
        $this->moderate('accepted',$comment);
    }

    public function reject(
        string|null $comment = null
        )
    {
        $this->moderate('rejected',$comment);
    }

    private function moderate(
        string $status,
        string|null $comment = null
        )
    {
        $this->verify();
        $this->isModerated = true;
        $this->status = $status;
        $this->updatedAt = TimeStamp::now();
        $this->comment = $comment;
    }

    private function verify()
    {
        if ($this->isModerated) {
            throw new ResourceAccessForbiddenException(
                'Task has been moderated'
            );
        }
    }

    public function export()
    {
        return [
            'isModerated' => $this->isModerated,
            'status' => $this->status,
            'updatedAt' => $this->updatedAt,
            'comment' => $this->comment
        ];
    }



}
