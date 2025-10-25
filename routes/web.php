<?php

interface Shape
{
    public function draw(): string;
}

class Circle implements Shape
{
    public function draw(): string
    {
        return 'Drawing Circle';
    }
}

class Square implements Shape
{
    public function draw(): string
    {
        return 'Drawing Square';
    }
}

class ShapeFactory
{
    public static function create($type): Shape
    {
        return match (strtolower($type)) {
            'circle' => new Circle,
            'square' => new Square,
            default => throw new Exception('Invalid shape type')
        };
    }
}

// Client
try {
    $shape = ShapeFactory::create('circle');
} catch (Exception $e) {
    dd($e->getMessage());
}

dd($shape->draw());
