<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
  die();
}

require_once($_SERVER["DOCUMENT_ROOT"] . "/symfony-integration/config_rabbitmq.php");

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

function rabbitmqSend($queue, $body)
{
  /**
   * Настройка
   */
  $connection = new AMQPStreamConnection(
    rabbitmq_host,
    rabbitmq_port,
    rabbitmq_user,
    rabbitmq_password
  );
  $channel = $connection->channel();
  $channel->queue_declare($queue, false, true, false, false);
  $properties = [
    'content_type' => 'application/json',
    'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
  ];

  /**
   * Создание сообщения
   */
  $msg = new AMQPMessage($body, $properties);
  $channel->basic_publish($msg, '', $queue);

  /**
   * Закрытие соединения
   */
  $channel->close();
  $connection->close();
}