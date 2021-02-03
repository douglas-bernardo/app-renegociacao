<?php

namespace App\Log;

abstract class Logger{

    protected $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
        file_put_contents($filename, '');
    }
    
    abstract function write($message);
}
