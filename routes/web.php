<?php

interface Observer
{
    public function update(string $message): void;
}

interface Subject
{
    public function attach(Observer $observer): void;

    public function detach(Observer $observer): void;

    public function notify(): void;
}

class NewsPublisher implements Subject
{
    private array $observers = [];

    private string $latestNews = '';

    public function attach(Observer $observer): void
    {
        $this->observers[] = $observer;
    }

    public function detach(Observer $observer): void
    {
        $this->observers = array_filter(
            $this->observers,
            fn ($o) => $o !== $observer
        );
    }

    public function notify(): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($this->latestNews);
        }
    }

    public function addNews(string $news): void
    {
        $this->latestNews = $news;
        $this->notify();
    }
}

class EmailSubscriber implements Observer
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function update(string $message): void
    {
        echo "{$this->name} received news: {$message}";
    }
}

$newsPublisher = new NewsPublisher;

$subscriber1 = new EmailSubscriber('Punyapal');
$subscriber2 = new EmailSubscriber('Bhushan');

$newsPublisher->attach($subscriber1);
$newsPublisher->attach($subscriber2);

$newsPublisher->addNews('New PHP version released!');
exit();
