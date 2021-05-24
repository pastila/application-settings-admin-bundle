<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Validator\Constraints;


use AppBundle\Service\Registration\PhoneVerification\PhoneVerificationRequestManager;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PhoneVerificationRequestValidator extends ConstraintValidator
{
  private $phoneVerificationRequestManager;

  public function __construct(PhoneVerificationRequestManager $manager)
  {
    $this->phoneVerificationRequestManager = $manager;
  }

  public function validate($value, Constraint $constraint)
  {
    if (!$constraint instanceof PhoneVerificationRequest)
    {
      throw new UnexpectedTypeException($constraint, PhoneVerificationRequest::class);
    }

    if (!$value instanceof PhoneVerificationAwareInterface)
    {
      throw new UnexpectedTypeException($value, PhoneVerificationAwareInterface::class);
    }

    $verificationRequest = $this->phoneVerificationRequestManager->getVerificationRequest();

    // 1. Если проверочный код не был создан, то его нельзя пропускать
    if (!$verificationRequest)
    {
      $this->context->buildViolation($constraint->invalidCodeMessage)
        ->setParameter('{code}', $value->getVerificationCode())
        ->addViolation();
      return ;
    }

    if (!$verificationRequest->getPhone())
    {
      throw new InvalidArgumentException('A phone verification request does not have a phone number');
    }

    if (!$verificationRequest->getVerificationCode())
    {
      throw new InvalidArgumentException('A phone verification request does not have a verification code');
    }

    // 2. Если код был создан, но не был отправлен, то его нельзя пропускать
    if (!$verificationRequest->getVerificationCodeSentAt()
      // 3. Если код или телефон не введены, то их нельзя пропускать
      || (!$verificationRequest->getVerificationCode() || !$verificationRequest->getPhone())
      // 4. Если код или телефон не совпадают с проверенными, то их нельзя пропускать
      || ($verificationRequest->getVerificationCode() !== $value->getVerificationCode()
        || $verificationRequest->getPhone() !== $value->getVerifiedPhone()))
    {
      $this->context->buildViolation($constraint->invalidCodeMessage)
        ->atPath('verificationCode')
        ->setParameter('{code}', $value->getVerificationCode())
        ->addViolation();
    }

    // 4. Если код был создан и отправлен, но его срок действия истек, то его нельзя пропускать
    if ($verificationRequest->isExpired())
    {
      $this->context->buildViolation($constraint->codeExpiredMessage)
        ->addViolation();
    }
  }
}
