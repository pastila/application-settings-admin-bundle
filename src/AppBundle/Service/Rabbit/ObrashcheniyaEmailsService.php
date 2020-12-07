<?php


namespace AppBundle\Service\Rabbit;

use AppBundle\Model\Obrashchenia\AppealDataParse;
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
   * @var AppealDataParse
   */
  protected $appealDataParse;

  /**
   * ObrashcheniyaEmailsService constructor.
   * @param EntityManagerInterface $entityManager
   * @param LoggerInterface $logger
   * @param ObrashcheniaBranchMailer $mailer
   */
  public function __construct(
    EntityManagerInterface $entityManager,
    LoggerInterface $logger,
    ObrashcheniaBranchMailer $mailer,
    AppealDataParse $appealDataParse
  )
  {
    $this->entityManager = $entityManager;
    $this->logger = $logger;
    $this->mailerBranch = $mailer;
    $this->appealDataParse = $appealDataParse;
  }

  public function execute(AMQPMessage $msg)
  {
    $data = json_decode($msg->body, true);
    if (empty($data))
    {
      $this->logger->error('Empty body from Obrashcheniya Emails in RabbitMq');
      return;
    }
    $this->logger->info(sprintf('Get data from bitrix by RabbitMq in appeal: $s', $msg->body));

    try
    {
      $modelAppealData = $this->appealDataParse->parse($data);
    } catch (\Exception $e)
    {
      $this->logger->error('Failed parsing obrashcheniya to branch: ' . $e->getMessage());
      throw $e;
    }
    try
    {
      $this->mailerBranch->send($modelAppealData);
    } catch (\Exception $e)
    {
      $this->logger->error('Failed send obrashcheniya to branch: ' . $e->getMessage());
      throw $e;
    }
  }
}