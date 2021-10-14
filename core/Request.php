<?php

namespace app\core;

class Request
{
    public function getPath(): string
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if ($position === false) {
            return $path;
        }
        return substr($path, 0, $position);
    }

    public function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function isPost(): bool
    {
        return $this->getMethod() === 'post';
    }

    public function isGet(): bool
    {
        return $this->getMethod() === 'get';
    }

    public function getBody():array
    {
        $bodyData = [];
        if ($this->isGet()) {
            $bodyData = array_filter($_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        } elseif ($this->isPost()){
            $bodyData = array_filter($_POST,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }
        return $bodyData;
    }
}
