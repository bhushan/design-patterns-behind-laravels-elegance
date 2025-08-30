<?php

interface Logger
{
    public function log(string $message): string;
}






























class FileLogger implements Logger
{
    public function log(string $message): string
    {
        return 'Logging to a file: '.$message.PHP_EOL;
    }
}































class DatabaseLogger implements Logger
{
    public function log(string $message): string
    {
        return 'Logging to a database: '.$message.PHP_EOL;
    }
}

$obj = new DatabaseLogger;

dd(
    $obj->log('Hello Ahmedabad')
);
