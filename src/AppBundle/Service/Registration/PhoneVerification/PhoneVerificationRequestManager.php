<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Service\Registration\PhoneVerification;

use Accurateweb\SmsBundle\Sms\Factory\SmsFactory;
use AppBundle\Exception\PhoneVerificationRequestManagerException;
use AppBundle\Exception\SmsDataException;
use AppBundle\Exception\SmsRequestException;
use AppBundle\Service\Sms\SmsInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Есть сервис PhoneVerificationRequestManager, который отвечает за хранение и поиск PhoneConfirmationRequest'ов
 *
 * Class PhoneVerificationRequestManager
 * @package AppBundle\Service\Registration\PhoneVerification
 */
class PhoneVerificationRequestManager
{
  const SESSION_KEY = 'PHONE_VERIFICATION_REQUEST';

  /**
   * @var PhoneVerificationRequest
   */
  private $phoneVerificationRequest;

  private $logger;

  private $smsService;

  private $session;

  private $smsFactory;

  public function __construct(
    SessionInterface $session,
    LoggerInterface $logger,
    SmsInterface $smsService,
    SmsFactory $smsFactory
  )
  {
    $this->session = $session;
    $this->logger = $logger;
    $this->smsService = $smsService;
    $this->smsFactory = $smsFactory;
  }

  /**
   * Отправляет код проверки на указанный номер и сохраняет запрос в качестве текущего
   *
   * @param PhoneVerificationRequest $request
   * @return bool - true если код отправлен успешно
   * @throws PhoneVerificationRequestManagerException
   */
  public function sendVerificationCode(PhoneVerificationRequest $request) //отправляет запрос и сохраняет в сессии
  {
    $currentTime = new \DateTime();

    if ($this->getVerificationRequest() && $this->getVerificationRequest()->getVerificationCodeSentAt())
    {
      /**
       * Если в сессии есть запись, о уже отправленной смс
       * Проверям, что она была отправлена не раньше 30 сек
       */
      $sessionTime = clone $this->getVerificationRequest()->getVerificationCodeSentAt();
      $sessionTime->add(new \DateInterval('PT30S'));

      if ($sessionTime > $currentTime)
      {
        throw new PhoneVerificationRequestManagerException('Превышено количество запросов для отправки sms кодов!');
      }
    }

    $message = $this->smsFactory->createSms('registration', [
      'CODE' => $request->getVerificationCode()
    ]);

    try
    {
      $this->smsService->send($request, $message->getText());
      $this->phoneVerificationRequest = $request;
      $this->phoneVerificationRequest->setVerificationCodeSentAt(new \DateTime());
      $this->persist();

      return true;
    }
    catch (SmsRequestException $exception)
    {
      $this->logger->error(
        sprintf('Exception in sending sms to phone %s, exception: %s', $request->getPhone(), $exception));
    }
    catch (SmsDataException $exception)
    {
      $this->logger->error(
        sprintf('Data error when sending SMS to phone %s, exception: %s', $request->getPhone(), $exception));
    }
    catch (\Exception $exception)
    {
      $this->logger->error(
        sprintf('Exception when sending SMS to phone %s, exception: %s', $request->getPhone(), $exception));
    }

    return false;
  }

  public function createVerificationRequest($phone): PhoneVerificationRequest // Создает запрос, генерит код, сохраняет в сессии
  {
    $request = new PhoneVerificationRequest();
    $code = (string)random_int(10000, 99999);

    $request->setPhone($phone);
    $request->setVerificationCode($code);
    $request->setCreatedAt(new \DateTime());

    return $request;
  }

  /**
   * Возвращает текущий запрос
   *
   * @return PhoneVerificationRequest|null
   */
  public function getVerificationRequest(): ?PhoneVerificationRequest
  {
    if ($this->phoneVerificationRequest === null)
    {
      $this->fetch();
    }

    return $this->phoneVerificationRequest;
  }

  private function persist()
  {
    $this->session->set(self::SESSION_KEY, serialize($this->getVerificationRequest()));
  }

  public function fetch()
  {
    $request = null;

    if ($this->session->has(self::SESSION_KEY))
    {
      try
      {
        $request = new PhoneVerificationRequest();
        $request->unserialize($this->session->get(self::SESSION_KEY));
      }
      catch (\InvalidArgumentException $e)
      {
        $request = null;
        $this->logger->warn(sprintf('Unable to load stored phone verification request: %s', $e->getMessage()));
      }
    }

    $this->phoneVerificationRequest = $request;
  }
}
