<?php

declare(strict_types=1);
namespace tasksvc\services;
use \core\exceptions\BadRequestException;
use \core\exceptions\RecordNotFoundException;
use \tasksvc\factories\TaskFactory;
use tasksvc\models\TaskModel;
use \glyphic\TypeOf;
use \glyphic\TimeStamp;
use \glyphic\UniqueId;

class TaskDBService {

    private TaskModel $task;

    public function __construct(
        TaskModel $task
        )
    {
        $this->task = $task;
    }

    public function save()
    {
        # Remove task summary from previous status
        $this->moveFromPreviousStatus();

        # Save task summary to current status
        $this->moveToCurrentStatus();

        # Save task summary to user directory
        $this->saveToUser();

        # Finally, save entire task to all directory
        $this->makeRecord();

    }

    public function moveFromPreviousStatus()
    {
        # Building the path to status directory
        $path = Self::statusPath($this->task->getStatus()->previous()).$this->task->getId();
        if (file_exists($path)) {
            unlink($path);
        }
    }

    public function moveToCurrentStatus()
    {
        # Building the path to status directory
        $path = Self::statusPath($this->task->getStatus()->current()).$this->task->getId();
        // header('Content-Type: application/json');
        // echo $this->indexify();
        file_put_contents($path,$this->indexify());
    }

    public function saveToUser()
    {
        # Building the path to user directory
        $path = Self::statusPath('users').$this->task->getUser()->email().'/';

        # Create user directory when not there
        if (!file_exists($path)) mkdir($path);

        # Save task summary
        file_put_contents($path.$this->task->getId(),$this->indexify());
    }

    public function makeRecord()
    {
        # Building the path to user directory
        $path = Self::statusPath('all').$this->task->getId();

        # Save entire task data
        file_put_contents($path,json_encode($this->task->export()));
    }

    public static function getTask(
        string $status,
        string $taskId
        )
    {
        $path = Self::statusPath($status).$taskId;
        if (!file_exists($path)) {
            throw new RecordNotFoundException(
                'Task item (id: '.$taskId.') does not exist'
            );
        }
        return (new TaskFactory(json_decode(file_get_contents($path),TRUE)))->create();
    }

    public static function fetchTasks(
        string $status,
        int $page
        )
    {
        $path = Self::statusPath($status);
        return Self::getFromDir($path,$page);
    }

    private static function getFromDir(
        string $path,
        int $page
        )
    {
        $files = scandir($path);
        $item = 1;
        $perPage = 8;
        $pageCounter = 1;
        $result = [];
        foreach ($files as $file) {
            if ($file!=='.'&&$file!=='..'&&$file!=='node.txt') {
                if ($pageCounter===$page) {
                    $filePath = $path.$file;
                    array_push($result,json_decode(file_get_contents($filePath),TRUE));
                }
                $item++;
                if ($item===$perPage) {
                    $item = 1;
                }
            }
        }
        return $result;
    }

    public function indexify()
    {
        return json_encode([
            'id' => $this->task->getId(),
            'email' => $this->task->getUser()->email(),
            'createdAt' => $this->task->getCreatedAt(),
            'todoCount' => $this->task->countTodos(),
            'type' => $this->task->getType(),
            'about' => substr($this->task->getDescription(),0,50)
        ]);
    }

    private static function statusPath(
        string $status
        )
    {
        return ROOT.'/data/tasksvc/'.$status.'/';
    }

}
