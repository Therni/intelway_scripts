<?php

class Logger
{

    var $file = false;
    var $log_dir = 'logs';

    function __construct($filename, $marker)
    {
        $this->openFile($filename, $marker);
    }

    function __destruct() {
        fclose($this->file);
    }

    function openFile($filename = 'default', $marker = 'default')
    {
        $filepath = $this->log_dir . '/' . $filename . '_' . '.txt';
        $this->file = fopen($filepath, 'a');
        fwrite($this->file, date("Y-m-d H:i") . " " . $marker . "\r\n" . "\r\n");
    }

    function info($message)
    {
        if ($message){
            fwrite($this->file, $message . "\r\n");
        }
    }
}

class Selector
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

    function openFile($filename = 'default')
    {
        $filepath = $this->log_dir . '/' . $filename . '.txt';
        $this->file = fopen($filepath, 'a');

    }

    function note($message)
    {
        if ($message){
            fwrite($this->file, $message);
        }
    }
}