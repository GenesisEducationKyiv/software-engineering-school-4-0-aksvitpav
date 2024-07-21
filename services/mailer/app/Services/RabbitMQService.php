<?php

namespace App\Services;

use App\Interfaces\Services\RabbitMQServiceInterface;
use ErrorException;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService implements RabbitMQServiceInterface
{
    private AMQPStreamConnection $connection;
    private AMQPChannel $channel;

    public function __construct()
    {
        /** @var string $host */
        $host = env('RABBITMQ_HOST');
        /** @var string $port */
        $port = env('RABBITMQ_PORT');
        /** @var string $user */
        $user = env('RABBITMQ_USER');
        /** @var string $password */
        $password = env('RABBITMQ_PASSWORD');
        /** @var string $vhost */
        $vhost = env('RABBITMQ_VHOST');

        $this->connection = new AMQPStreamConnection(
            host: $host,
            port: $port,
            user: $user,
            password: $password,
            vhost: $vhost,
        );

        $this->channel = $this->connection->channel();
    }

    public function sendMessage(string $queue, string $message): void
    {
        $this->channel->queue_declare($queue, false, true, false, false);

        $msg = new AMQPMessage($message);
        $this->channel->basic_publish($msg, '', $queue);
    }

    /**
     * @throws ErrorException
     */
    public function consumeMessages(string $queue, callable $callback): void
    {
        $this->channel->queue_declare($queue, false, true, false, false);

        $this->channel->basic_consume($queue, '', false, true, false, false, $callback);

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }

    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
