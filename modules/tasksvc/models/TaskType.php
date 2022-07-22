<?php

declare(strict_types=1);
namespace tasksvc\models;
use \core\exceptions\BadRequestException;

class TaskType {

    private string $type;

    public function __construct(
        string|null $type
        )
    {
        switch ($type) {
            case 'csv-task':
                $this->type = $type;
                break;

            case 'data-scraping':
                $this->type = $type;
                break;

            default:
                throw new BadRequestException(
                    'Invalid task type'
                );
                break;
        }
    }

    public function get()
    {
        return $this->type;
    }

}
