<?php

class Logger
{

    var $file = false;
    var $log_dir = 'logs';

    function __construct($filename)
    {
        $this->openFile($filename);
    }

    function __destruct() {
        fclose($this->file);
    }

    function file() {
        return 'Hello';
    }

    function openFile($filename = 'default')
    {
        $filepath = $this->log_dir . '/' . $filename . '_' . date("Y-m-d_H-i-s") . '.txt';
        $this->file = fopen($filepath, 'a+');
    }

    function info($message)
    {
        if ($message){
            fwrite($this->file, $message . '\n');
        }
    }
} 