<?php

declare(strict_types=1);
namespace tasksvc\services;
use \core\exceptions\BadRequestException;
use \glyphic\TypeOf;
use \glyphic\TimeStamp;
use \glyphic\UniqueId;

class StatusManager {

    private string $previous;
    private string $current;
    private string $updatedAt;

    public function __construct(
        array $statusArr
        )
    {
        $this->previous = Self::validateStatus($statusArr['previous'] ?? null);
        $this->current = Self::validateStatus($statusArr['current'] ?? null);
        $this->updatedAt = $statusArr['updatedAt'] ?? TimeStamp::now();
    }

    private static function validateStatus(
        string|null $status
        )
    {
        switch ($status) {
            case 'completed':
                return 'completed';
                break;

            case 'pending':
                return 'pending';
                break;

            case 'rejected':
                return 'rejected';
                break;

            case null:
                return 'new';
                break;

            case 'new':
                return 'new';
                break;

            default:
                throw new BadRequestException(
                    'Invalid task status'
                );
                break;
        }
    }

    public function toCompleted()
    {
        $this->previous = $this->current;
        $this->current = 'completed';
        $this->updatedAt = TimeStamp::now();
    }

    public function toPending()
    {
        $this->previous = $this->current;
        $this->current = 'pending';
        $this->updatedAt = TimeStamp::now();
    }

    public function toRejected()
    {
        $this->previous = $this->current;
        $this->current = 'rejected';
        $this->updatedAt = TimeStamp::now();
    }

    public function previous()
    {
        return $this->previous;
    }

    public function current()
    {
        return $this->current;
    }

    public function export()
    {
        return [
            'previous' => $this->previous,
            'current' => $this->current,
            'updatedAt' => $this->updatedAt
        ];
    }

}
