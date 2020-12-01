<?php


namespace AppBundle\Service\Rabbit;

use AppBundle\Entity\Obrashcheniya\ObrashcheniyaEmail;
use AppBundle\Entity\User\User;
use AppBundle\Model\Obrashchenia\ObrashcheniaBranch;
use AppBundle\Service\Obrashcheniya\ObrashcheniaBranchMailer;
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
   * @var ObrashcheniaBranchMailer
   */
  protected $mailerBranch;

  /**
   * ObrashcheniyaEmailsService constructor.
   * @param EntityManagerInterface $entityManager
   * @param LoggerInterface $logger
   * @param ObrashcheniaBranchMailer $mailer
   */
  public function __construct(
    EntityManagerInterface $entityManager,
    LoggerInterface $logger,
    ObrashcheniaBranchMailer $mailer
  )
  {
    $this->entityManager = $entityManager;
    $this->logger = $logger;
    $this->mailerBranch = $mailer;
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

    $obrashcheniyaEmail = new ObrashcheniyaEmail();
    $obrashcheniyaEmail->setAuthor($author);
    $obrashcheniyaEmail->setData($msg->body);
    $this->entityManager->persist($obrashcheniyaEmail);
    $this->entityManager->flush();

    try
    {
      $modelObrashcheniaBranch = new ObrashcheniaBranch($data);
    } catch (\Exception $e)
    {
      $this->logger->error('Failed parsing obrashcheniya to branch: ' . $e->getMessage());
      throw $e;
    }
    try
    {
      $this->mailerBranch->send($modelObrashcheniaBranch);
    } catch (\Exception $e)
    {
      $this->logger->error('Failed send obrashcheniya to branch: ' . $e->getMessage());
      throw $e;
    }
  }
}