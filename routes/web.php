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

// app()->singleton('hello', function () {
//    return new Hello;
// });


















































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
// $obj->increment();
// dd($obj->increment());
