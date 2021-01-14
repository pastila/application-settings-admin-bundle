<?php


namespace AppBundle\Service\Rabbit;

use AppBundle\Entity\Obrashcheniya\ObrashcheniyaFile;
use AppBundle\Entity\User\User;
use AppBundle\Service\Obrashcheniya\AppealToUserConnector;
use Doctrine\DBAL\Exception\InvalidArgumentException;
use Doctrine\ORM\EntityManagerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

class ObrashcheniyaFilesService implements ConsumerInterface
{
  /**
   * @var AppealToUserConnector
   */
  private $connector;

  /**
   * @var LoggerInterface
   */
  protected $logger;

  /**
   * ObrashcheniyaFilesService constructor.
   * @param EntityManagerInterface $entityManager
   * @param LoggerInterface $logger
   */
  public function __construct(
    AppealToUserConnector $connector,
    LoggerInterface $logger
  )
  {
    $this->connector = $connector;
    $this->logger = $logger;
  }

  public function execute(AMQPMessage $msg)
  {
    try
    {
      $this->connector->saveAppealToUserConnection(json_decode($msg->body, true));
    }
    catch (\InvalidArgumentException $e)
    {
      $this->logger->error($e);

      return ConsumerInterface::MSG_REJECT;
    }
    catch (\Exception $e)
    {
      $this->logger->error($e);

      return ConsumerInterface::MSG_REJECT_REQUEUE;
    }
  }
}
