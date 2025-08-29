<?php

abstract class Handler
{
    private ?Handler $next = null;

    public function setNext(Handler $handler): Handler
    {
        $this->next = $handler;

        return $handler;
    }

    public function handle(string $request): ?string
    {
        if ($this->next) {
            return $this->next->handle($request);
        }

        return null;
    }
}

class AuthHandler extends Handler
{
    public function handle(string $request): ?string
    {
        if ($request === 'unauthorized') {
            return 'AuthHandler: Access Denied!';
        }

        return parent::handle($request);
    }
}

class LoggerHandler extends Handler
{
    public function handle(string $request): ?string
    {
        dump("LoggerHandler: Logging request -> $request");

        return parent::handle($request);
    }
}

class DataHandler extends Handler
{
    public function handle(string $request): ?string
    {
        if ($request === 'data') {
            return 'DataHandler: Processing data...';
        }

        return parent::handle($request);
    }
}

$auth = new AuthHandler;
$logger = new LoggerHandler;
$data = new DataHandler;

$auth->setNext($logger)->setNext($data);

dd(
    $auth->handle('unauthorized'),
    $auth->handle('data'),
    $auth->handle('other')
);
