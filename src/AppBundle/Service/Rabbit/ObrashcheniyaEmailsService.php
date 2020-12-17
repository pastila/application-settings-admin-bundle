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
    $data = json_decode($msg->body, true);

    $this->logger->info(sprintf('Get data from bitrix by RabbitMq in appeal: %s', $msg->body));

    try
    {
      $modelAppealData = $this->appealDataParse->parse($data);
    }
    catch (\Exception $e)
    {
      $this->logger->error(sprintf('Exception in parsing appeal: %s', $e));

      /**
       * Установить для обращения статус, что оно не было отправлено
       */
      $this->bitrixHelper->updatePropertyElementValue(11, $data[2]['ID'], 'SEND_REVIEW', 9, 9, null);

      /**
       * Вернуть флаг, что отклонено и удаляем сообщение из очереди
       */
      return ConsumerInterface::MSG_REJECT;
    }

    try
    {
      foreach ($modelAppealData->getEmailsTo() as $email)
      {
        $this->mailerBranch->send($modelAppealData, $email);
      }
    }
    catch (\Swift_TransportException $e)
    {
      $this->logger->error(sprintf('Transport Exception in sending appeal to branch company: %s',  $e));

      return ConsumerInterface::MSG_REJECT_REQUEUE;
    }
    catch (\Exception $e2)
    {
      $this->logger->error(sprintf('Exception in sending appeal to branch company: %s', $e2));

      /**
       * Установить для обращения статус, что оно не было отправлено
       */
      $this->bitrixHelper->updatePropertyElementValue(11, $data[2]['ID'], 'SEND_REVIEW', 9, 9, null);

      /**
       * Вернуть флаг, что отклонено и удаляем сообщение из очереди
       */
      return ConsumerInterface::MSG_REJECT;
    }

    # Обновляем статус обращения
    $this->bitrixHelper->updatePropertyElementValue(11, $data[2]['ID'], 'SEND_REVIEW', 3, 3, null);
    $this->bitrixHelper->updatePropertyElementValue(11, $data[2]['ID'], 'SEND_MESSAGE', date('y-m-d'), date('y'), date('y') . '.0000');

    # Отправка сообщения пользователю, что обращение отправлено
    $this->userMailer->send($modelAppealData);

    /**
     * Ок
     */
    return ConsumerInterface::MSG_ACK;
  }
}
