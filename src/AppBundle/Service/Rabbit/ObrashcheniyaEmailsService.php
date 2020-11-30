<?php


namespace AppBundle\Service\Rabbit;

use AppBundle\Entity\Obrashcheniya\ObrashcheniyaEmail;
use AppBundle\Entity\Obrashcheniya\ObrashcheniyaFile;
use AppBundle\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

class ObrashcheniyaEmailsService implements ConsumerInterface
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
   * ObrashcheniyaEmailsService constructor.
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
      $this->logger->error('Empty body from Obrashcheniya Emails in RabbitMq');
      return;
    }
    $author = $this->entityManager->getRepository(User::class)
      ->findOneBy(['login' => key_exists('login', $data) ? $data['login'] : null]);

    $model = new ObrashcheniyaEmail();
    $model->setAuthor($author);
    $model->setData($msg->body);
    $this->entityManager->persist($model);
    $this->entityManager->flush();
  }

  private function sendVerificationEmail($response)
  {
//    $data = json_decode($response, true);
    $fp = fopen('obrashcheniya_emails' . bin2hex(random_bytes(5)) . '.txt', "w");
    fwrite($fp, $response);
    fclose($fp);
  }
}