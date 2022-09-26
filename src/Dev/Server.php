<?php

namespace Dev;

class Server
{
    private $publicDir;
    private $resourcePath;

    public function __construct($publicDir)
    {
        $this->publicDir = $publicDir;
        $this->createResourcePath();
    }

    public function createResourcePath()
    {
        $endPoints = $this->uriToArray();
        $this->resourcePath =
            SERVER_ROOT . $this->publicDir . implode("/", $endPoints);
        return;
    }

    private static function uriToArray()
    {
        return explode("/", explode("?",$_SERVER['REQUEST_URI'])[0]);
    }

    public function resourceRouting()
    {
        if (null === $this->getFileTypeRequest()) {
            $this->resourcePath = $this->resourcePath . ".php";
        }

        if (!file_exists($this->resourcePath)) {
            http_response_code(404);
            header("Content-Type: application/json");
            echo '{"error":"Route not found"}';
            exit();
        }
    }

    public function serve()
    {
        $this->resourceRouting();
        $this->setContentType();
        require $this->resourcePath;
    }

    public function getFileTypeRequest()
    {
        $endPoints = $this->uriToArray();
        $resource = array_pop($endPoints);
        $endPoint = explode(".", $resource);
        if (!isset($endPoint[1])) {
            return null;
        }
        return $endPoint[1];
    }

    public function setContentType()
    {
        switch ($this->getFileTypeRequest()) {
            case "json":
                header("Content-Type: application/json");
                break;
            case "png":
                header("Content-Type: image/png");
                break;
            case "gif":
                header("Content-Type: image/gif");
                break;
            case "tiff":
                header("Content-Type: image/tiff");
                break;
            case "jpg":
                header("Content-Type: image/jpeg");
                break;
            case "jpeg":
                header("Content-Type: image/jpeg");
                break;
            case "css":
                header("Content-Type: text/css");
                break;
            case "csv":
                header("Content-Type: text/csv");
                break;
            case "html":
                header("Content-Type: text/html");
                break;
            case "xml":
                header("Content-Type: text/xml");
                break;
            case "txt":
                header("Content-Type: text/plain");
                break;
            default:
                // code...
                break;
        }
    }
}
