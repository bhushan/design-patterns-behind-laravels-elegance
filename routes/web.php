<?php

 cache()->set('meetup', 'Laravel Ahmedabad');
 dd(
    cache()->get('meetup')
 );










































//class Human
//{
//    public function walk(): string
//    {
//        return 'walk';
//    }
//
//    public function eat(): string
//    {
//        return 'eat';
//    }
//
//    public function sleep(): string
//    {
//        return 'sleep';
//    }
//}
//
//app()->bind('human', function () {
//    return new Human;
//});
//
//class HumanFacade
//{
//    public static function __callStatic($method, $args)
//    {
//        return app()->make('human')->{$method}(...$args);
//    }
//}
//
//dd(
//    HumanFacade::sleep()
//);
