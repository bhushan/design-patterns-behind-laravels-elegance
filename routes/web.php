<?php

abstract class Vehicle
{
    public function __construct(protected string $brand) {}

    abstract public function start(): string;

    public function getBrand(): string
    {
        return $this->brand;
    }
}

























class Car extends Vehicle
{
    public function start(): string
    {
        return $this->getBrand().' car engine started 🚗';
    }
}































class Bike extends Vehicle
{
    public function start(): string
    {
        return $this->getBrand().' bike engine started 🏍️';
    }
}

$car = new Car('Toyota');
dump($car->start());

$bike = new Bike('Yamaha');
dd($bike->start());
