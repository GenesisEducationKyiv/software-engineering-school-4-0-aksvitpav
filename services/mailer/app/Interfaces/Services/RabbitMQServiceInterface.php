<?php

namespace App\Interfaces\Services;

interface RabbitMQServiceInterface
{
    public function sendMessage(string $queue, string $message): void;
    public function consumeMessages(string $queue, callable $callback): void;
}
