<?php

interface PizzaBuilderInterface
{
    public function setDough(string $dough): self;

    public function setSauce(string $sauce): self;

    public function setTopping(string $topping): self;

    public function build(): Pizza;
}

class Pizza
{
    public string $dough;

    public string $sauce;

    public string $topping;

    public function __toString(): string
    {
        return "Pizza with {$this->dough} dough, {$this->sauce} sauce, and {$this->topping} topping.";
    }
}

class PizzaBuilder implements PizzaBuilderInterface
{
    private Pizza $pizza;

    public function __construct()
    {
        $this->pizza = new Pizza;
    }

    public function setDough(string $dough): self
    {
        $this->pizza->dough = $dough;

        return $this;
    }

    public function setSauce(string $sauce): self
    {
        $this->pizza->sauce = $sauce;

        return $this;
    }

    public function setTopping(string $topping): self
    {
        $this->pizza->topping = $topping;

        return $this;
    }

    public function build(): Pizza
    {
        return $this->pizza;
    }
}

$builder = new PizzaBuilder;
$pizza = $builder->setDough('thin')
    ->setSauce('tomato')
    ->setTopping('cheese')
    ->build();

dd((string) $pizza);
