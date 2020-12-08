<?php


namespace AppBundle\Service\Rabbit;

use AppBundle\Entity\Obrashcheniya\ObrashcheniyaFile;
use AppBundle\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

class ObrashcheniyaFilesService implements ConsumerInterface
{
  /**
   * @var EntityManagerInterface
   */
  private $entityManager;

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
    EntityManagerInterface $entityManager,
    LoggerInterface $logger
  )
  {
    $this->entityManager = $entityManager;
    $this->logger = $logger;
  }

  public function execute(AMQPMessage $msg)
  {
    $data = json_decode($msg->body, true);
    if (empty($data))
    {
      $this->logger->error('Empty body from Obrashcheniya Files in RabbitMq');
      return;
    }
    $author = $this->entityManager->getRepository(User::class)
      ->findOneBy(['login' => key_exists('user_login', $data) ? $data['user_login'] : null]);

    $model = new ObrashcheniyaFile();
    $model->setAuthor($author);
    $model->setType(key_exists('file_type', $data) ? $data['file_type'] : null);
    $model->setFile(key_exists('file_name', $data) ? $data['file_name'] : null);
    $model->setBitrixId(key_exists('obrashcheniya_id', $data) ? $data['obrashcheniya_id'] : null);
    $model->setImageNumber(key_exists('imageNumber', $data) ? $data['imageNumber'] : null);
    $this->entityManager->persist($model);
    $this->entityManager->flush();
  }
}