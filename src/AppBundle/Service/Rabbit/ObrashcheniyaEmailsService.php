<?php


namespace AppBundle\Service\Rabbit;

use AppBundle\Model\Obrashchenia\AppealDataParse;
use AppBundle\Service\Obrashcheniya\ObrashcheniaBranchMailer;
use AppBundle\Service\Obrashcheniya\ObrashcheniaUserMailer;
use AppBundle\Util\BitrixHelper;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

class ObrashcheniyaEmailsService implements ConsumerInterface
{
  /**
   * @var LoggerInterface
   */
  protected $logger;

  /**
   * @var ObrashcheniaBranchMailer
   */
  protected $mailerBranch;

  protected $userMailer;

  /**
   * @var AppealDataParse
   */
  protected $appealDataParse;

  /**
   * @var BitrixHelper
   */
  private $bitrixHelper;

  /**
   * ObrashcheniyaEmailsService constructor.
   * @param LoggerInterface $logger
   * @param ObrashcheniaBranchMailer $mailerBranch
   * @param AppealDataParse $appealDataParse
   */
  public function __construct(
    LoggerInterface $logger,
    ObrashcheniaBranchMailer $mailerBranch,
    ObrashcheniaUserMailer $userMailer,
    AppealDataParse $appealDataParse,
    BitrixHelper $bitrixHelper
  )
  {
    $this->logger = $logger;
    $this->mailerBranch = $mailerBranch;
    $this->userMailer = $userMailer;
    $this->appealDataParse = $appealDataParse;
    $this->bitrixHelper = $bitrixHelper;
  }


  public function execute(AMQPMessage $msg)
  {
    $appealHasBeenSent = false;

    try
    {
      $data = json_decode($msg->body, true);

      $this->logger->info(sprintf('Get data from bitrix by RabbitMq in appeal: %s', $msg->body));

      $modelAppealData = $this->appealDataParse->parse($data);

      $this->mailerBranch->send($modelAppealData);

      $appealHasBeenSent = true;

      # Обновляем статус обращения
      $this->bitrixHelper->updatePropertyElementValue(11, $data[2]['ID'], 'SEND_REVIEW', 3, 3, null);
      $this->bitrixHelper->updatePropertyElementValue(11, $data[2]['ID'], 'SEND_MESSAGE', date('y-m-d'), date('y'), date('y').'.0000');

      # Отправляем письмо юзеру
      $this->userMailer->send($modelAppealData);
    }
    catch (\Exception $e)
    {
      if ($appealHasBeenSent)
      {

      }
    }
  }
}
