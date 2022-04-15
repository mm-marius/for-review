<?php
namespace App\Models;

class Response
{
    public $data;
    public $success;
    public $errorMessage;
    public $code;
    public function __construct($success, $code, $content = null, $error = '')
    {
        $this->success = $success;
        $this->code = $code;
        $this->data = $content;
        $this->errorMessage = $error;
    }
}