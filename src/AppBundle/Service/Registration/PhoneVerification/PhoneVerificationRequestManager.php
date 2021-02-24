<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Service\Registration\PhoneVerification;


use AppBundle\Entity\User\User;
use AppBundle\Exception\PhoneVerificationRequestManagerException;
use AppBundle\Service\Sms\SmsAtomPark;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Есть сервис PhoneVerificationRequestManager, который отвечает за хранение и поиск PhoneConfirmationRequest'ов
 *
 * @Todo: подписаться на Kernel::shutdown и сохранить текущий PhoneVerificationRequest в
 *
 * Class PhoneVerificationRequestManager
 * @package AppBundle\Service\Registration\PhoneVerification
 */
class PhoneVerificationRequestManager
{
  const SESSION_KEY = 'PHONE_VERIFICATION_REQUEST';

  private $phoneVerificationRequest;

  private $logger;

  private $smsService;

  private $session;

  public function __construct(
    SessionInterface  $session,
    LoggerInterface $logger,
    SmsAtomPark $smsService)
  {
    $this->session = $session;
    $this->logger = $logger;
    $this->smsService = $smsService;

    $this->fetch();
  }

  public function sendVerificationCode(PhoneVerificationRequest $request) //отправляет запрос и сохраняет в сессии
  {
    $currentTime = new \DateTime();

    if ($request->getVerificationCodeSentAt())
    {
      /**
       * Если в сессии есть запись, о уже отправленной смс
       * Проверям, что она была отправлена не раньше 30 сек
       */
      $sessionTime = clone $request->getVerificationCodeSentAt();
      $sessionTime->add(new DateInterval('PT30S'));

      if ($sessionTime > $currentTime)
      {
        throw new PhoneVerificationRequestManagerException('Превышено количество запросов для отправки sms кодов!');

//        return new JsonResponse([
//          'message' => 'Превышено количество запросов для отправки sms кодов!'
//        ], 429);
      }
    }

//    try
//    {
//      $this->smsService->sendCommand('registerSender', [
//        'name' => "bezbahil",
//        'country' => "ru"
//      ]);
//      $this->smsService->sendCommand("sendSMS", [
//        'sender' => "bezbahil",
//        'text' => "Ваш код - $smsCode",
//        'phone' => $phone,
//        'datetime' => "",
//        'sms_lifetime' => "0"
//      ]);
//    } catch (SmsRequestException $exception)
//    {
//      $this->get('logger')->error(sprintf('Exception in sending sms to phone %s, exception:  %s', $phone, $exception));
//      return new JsonResponse([
//        'status' => 'error'
//      ]);
//    } catch (SmsDataException $exception)
//    {
//      $this->get('logger')->error(sprintf('Data error when sending SMS to phone %s, exception:  %s', $phone, $exception));
//      return new JsonResponse([
//        'status' => 'error'
//      ]);
//    } catch (\Exception $exception)
//    {
//      $this->get('logger')->error(sprintf('Exception when sending SMS to phone %s, exception:  %s', $phone, $exception));
//      return new JsonResponse([
//        'status' => 'error'
//      ]);
//    }
  }

  public function createVerificationRequest($phone): PhoneVerificationRequest // Создает запрос, генерит код, сохраняет в сессии
  {
//    $phone = $request->get('phone');
//    $user = $this->userManager->findUserBy([
//      'phone' => $phone
//    ]);
//    if ($user)
//    {
//      return new JsonResponse([
//        'message' => 'Пользователь с таким номером телефона уже существует!'
//      ], 406);
//    }

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
    return $this->phoneVerificationRequest;
  }

  public function validatePhoneVerificationRequest(PhoneVerificationRequest $request, User $user)
  {
    //@Todo: 1. Проверить, что пользователь есть, и что введенный код совпадает с сгенерированным
    return $this->id || (!empty($this->codePhoneConfirm) && $this->codePhoneConfirm === $this->codePhoneConfirmGenerated);

    //@Todo: 2. Проверить, что пользователь есть, и что введенный номер телефона совпадает с тем, для которого сгенерирован код
    return $this->id || (!empty($this->phone) && $this->phone === $this->phoneRequested);
  }

  public function persist()
  {
    $this->session->set(self::SESSION_KEY, serialize($this->phoneVerificationRequest));
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
