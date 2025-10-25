<?php

class UserRegistered
{
    public function __construct(public string $username) {}
}

class SendWelcomeEmail
{
    public function handle(UserRegistered $event): void
    {
        echo "Sending welcome email to {$event->username}\n";
    }
}

class EventDispatcher
{
    private array $listeners = [];

    public function listen(string $eventName, callable $listener): void
    {
        $this->listeners[$eventName][] = $listener;
    }

    public function dispatch(object $event): void
    {
        $eventName = get_class($event);

        if (! isset($this->listeners[$eventName]) || empty($this->listeners[$eventName])) {
            return;
        }

        foreach ($this->listeners[$eventName] as $listener) {
            $listener($event);
        }
    }
}

$dispatcher = new EventDispatcher;
$dispatcher->listen(UserRegistered::class, [new SendWelcomeEmail, 'handle']);

$dispatcher->dispatch(new UserRegistered('Bhushan'));
exit();
