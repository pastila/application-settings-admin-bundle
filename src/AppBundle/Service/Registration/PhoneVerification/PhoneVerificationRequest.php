<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Service\Registration\PhoneVerification;

use Exception;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhoneVerificationRequest implements \Serializable
{
  /**
   * Время жизни кода в секундах
   */
  const CODE_LIFETIME = 600;

  private $phone;
  private $verificationCode;

  /**
   * @var \DateTime
   */
  private $verificationCodeSentAt;

  /**
   * @var \DateTime
   */
  private $createdAt;

  /**
   * @return mixed
   */
  public function getPhone()
  {
    return $this->phone;
  }

  /**
   * @param mixed $phone
   * @return PhoneVerificationRequest
   */
  public function setPhone($phone)
  {
    $this->phone = $phone;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getVerificationCode()
  {
    return $this->verificationCode;
  }

  /**
   * @param mixed $verificationCode
   * @return PhoneVerificationRequest
   */
  public function setVerificationCode($verificationCode)
  {
    $this->verificationCode = $verificationCode;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getVerificationCodeSentAt()
  {
    return $this->verificationCodeSentAt;
  }

  /**
   * @param mixed $verificationCodeSentAt
   * @return PhoneVerificationRequest
   */
  public function setVerificationCodeSentAt($verificationCodeSentAt)
  {
    $this->verificationCodeSentAt = $verificationCodeSentAt;
    return $this;
  }

  /**
   * @return \DateTime
   */
  public function getCreatedAt(): \DateTime
  {
    return $this->createdAt;
  }

  /**
   * @param \DateTime $createdAt
   * @return PhoneVerificationRequest
   */
  public function setCreatedAt(\DateTime $createdAt): PhoneVerificationRequest
  {
    $this->createdAt = $createdAt;
    return $this;
  }



  /**
   * @inheritDoc
   */
  public function serialize()
  {
    return serialize(array(
      'phone' => $this->phone,
      'verification_code' => $this->verificationCode,
      'verification_code_sent_at' => $this->verificationCodeSentAt,
      'created_at' => $this->createdAt
    ));
  }

  /**
   * @inheritDoc
   */
  public function unserialize($serialized)
  {
    $resolver = new OptionsResolver();
    $resolver->setRequired(array(
      'phone',
      'verification_code',
      'verification_code_sent_at',
      'created_at'
    ));

    try
    {
      $values = $resolver->resolve(unserialize($serialized));
    }
    catch (\InvalidArgumentException $e)
    {
      throw $e;
    }

    $this->setPhone($values['phone']);
    $this->setCreatedAt($values['created_at']);
    $this->setVerificationCode($values['verification_code']);
    $this->setVerificationCodeSentAt($values['verification_code_sent_at']);
  }

  /**
   * Возвращает true, если этот запрос проверки кода просрочен
   *
   * @return bool
   */
  public function isExpired()
  {
    if (!$this->getVerificationCodeSentAt())
    {
      throw new \LogicException('A request code has not been sent');
    }

    $now = new \DateTime();

    return $now->getTimestamp() - $this->getVerificationCodeSentAt()->getTimestamp() > self::CODE_LIFETIME;
  }
}
