<?php

namespace app\core;

class Request
{
    private array $bodyData = [];

    /**
     * @return string
     */
    public function getPath(): string
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if ($position === false) {
            return $path;
        }
        return substr($path, 0, $position);
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function isPost(): bool
    {
        return $this->getMethod() === 'post';
    }

    /**
     * @return bool
     */
    public function isGet(): bool
    {
        return $this->getMethod() === 'get';
    }

    /**
     * @param array $bodyData
     */
    public function setBody(array $bodyData)
    {
        $this->bodyData = $bodyData;
    }

    /**
     * @return array
     */
    public function getBody(): array
    {
        $bodyData = [];

        if ($this->isGet()) {
            if (count($_GET) > 0) {
                foreach ($_GET as $key => $value){
                    $bodyData[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                }

            }
        } elseif ($this->isPost()) {
            if (count($_POST) > 0) {
                foreach ($_POST as $key => $value) {
                    $bodyData[$key]= filter_input(INPUT_POST, $key,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                }
            }
        }else{
            $bodyData = $this->bodyData;
        }
        return $bodyData;
    }

}
