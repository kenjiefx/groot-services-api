<?php

declare(strict_types=1);
namespace tasksvc;
use \tasksvc\TaskModel;
use \tasksvc\TodoModel;
use \glyphic\TypeOf;
use \glyphic\TimeStamp;
use \glyphic\UniqueId;
use \core\exceptions\RecordNotFoundException;

class TaskDB {

    public static function save(
        TaskModel $task,
        string $oldStatus,
        string $status
        )
    {
        if ($status==='new') {
            Self::saveUser($task);
        }
        if ($status===$oldStatus) {
            Self::post($task,$status);
            return;
        }
        # Remove the previous cached
        $previous = Self::path($oldStatus,$task->getId());
        if (file_exists($previous)) {
            unlink($previous);
        }
        Self::post($task,$status);
    }

    private static function post(
        TaskModel $task,
        string $status
        )
    {
        $path = TaskDB::path($status,$task->getId());
        file_put_contents($path,'');
        file_put_contents(ROOT.'/data/tasksvc/all/'.$task->getId().'.json',$task->toJson());
    }

    private static function saveUser(
        TaskModel $task
        )
    {
        $email = $task->getEmail();
        $path = ROOT.'/data/tasksvc/users/'.$email;
        if (!file_exists($path)) {
            mkdir($path);
        }
        file_put_contents($path.'/'.$task->getId(),'');
    }

    private static function path(
        string $status,
        string $id
        )
    {
        $path = ROOT.'/data/tasksvc/'.$status.'/'.$id;
        return $path;
    }

    private static function statusPath(
        string $status
        )
    {
        return ROOT.'/data/tasksvc/'.$status;
    }

    private static function get(
        string $path
        )
    {
        if (!file_exists($path)) {
            throw new RecordNotFoundException(
                'Task path '.$path.' do not exist'
            );
        }
        return json_decode(file_get_contents($path),TRUE);
    }

    public static function getById(
        string $taskId
        )
    {
        $path = ROOT.'/data/tasksvc/all/'.$taskId.'.json';
        if (!file_exists($path)) {
            throw new RecordNotFoundException(
                'Task Id '.$taskId.' not found'
            );
        }
        return Self::get($path);
    }

    private static function getFromDir(
        string $dirPath,
        int $page
        )
    {
        $files = scandir($dirPath);
        $item = 1;
        $perPage = 20;
        $pageCounter = 1;
        $result = [];
        foreach ($files as $file) {
            if ($file!=='.'&&$file!=='..'&&$file!=='node.txt') {
                if ($pageCounter===$page) {
                    array_push($result,explode('.',$file)[0]);
                }
                $item++;
                if ($item===$perPage) {
                    $item = 1;
                }
            }
        }
        return $result;
    }

    public static function load(
        string $status,
        int $page
        )
    {
        $statusPath = Self::statusPath($status);
        return Self::getFromDir($statusPath,$page);
    }

    public static function getTasksByUser(
        string $email,
        int $page
        )
    {
        $path = ROOT.'/data/tasksvc/users/'.$email;
        if (!file_exists($path)) {
            throw new RecordNotFoundException(
                'Record not found under '.$email
            );
        }
        return Self::getFromDir($path,$page);
    }



}
