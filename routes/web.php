<?php

class Human
{
    public function walk()
    {
        dump('walk');

        return $this;
    }

    public function eat()
    {
        dump('eat');

        return $this;
    }

    public function sleep()
    {
        dump('sleep');

        return $this;
    }
}

(new Human)->eat()->walk()->sleep();
