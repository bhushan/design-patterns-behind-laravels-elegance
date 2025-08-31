<?php

class Hello
{
    public function world(): string
    {
        return 'Hello World';
    }
}

app()->bind('hello', function () {
    return new Hello;
});

// app()->bind(Hello::class, function () {
//    return new Hello;
// });

$obj = app()->make('hello');
dd($obj->world());


















































// class Hello
// {
//    public int $counter = 0;
//
//    public function world(): string
//    {
//        return 'Hello World';
//    }
//
//    public function increment(): int
//    {
//        $this->counter++;
//
//        return $this->counter;
//    }
// }
//
// // app()->bind('hello', function () {
// //    return new Hello;
// // });
//
// // app()->bind(Hello::class, function () {
// //    return new Hello;
// // });
//
// app()->singleton('hello', function () {
//    return new Hello;
// });
//
// $obj = app()->make('hello');
// $obj->increment();
//
// $obj = app()->make('hello');
// $obj->increment();
// $obj = app()->make('hello');
// $obj->increment();
//
// $obj = app()->make('hello');
// $value = $obj->increment();
// dd($value);







































//interface Logger
//{
//    public function log(string $message): string;
//}
//
//class FileLogger implements Logger
//{
//    public function log(string $message): string
//    {
//        return 'Logging to a file: ' . $message . PHP_EOL;
//    }
//}
//
//class DatabaseLogger implements Logger
//{
//    public function log(string $message): string
//    {
//        return 'Logging to a database: ' . $message . PHP_EOL;
//    }
//}
//
//app()->bind('logger', function () {
//$shouldUseFileLogger = true;
//
//if($shouldUseFileLogger) {
//    return new FileLogger();
//}
//
//    return new DatabaseLogger();
//});
//
//$obj = app()->make('logger');
//
//dd(
//    $obj->log('Hello Ahmedabad')
//);
