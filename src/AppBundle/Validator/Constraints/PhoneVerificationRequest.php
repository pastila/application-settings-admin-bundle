<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class PhoneVerificationRequest
 * @package AppBundle\Validator\Constraints
 *
 * @Annotation
 */
class PhoneVerificationRequest extends Constraint
{
  public $invalidCodeMessage = 'Неверный проверочный код {code}';

  public $codeExpiredMessage = 'Срок действия проверочного кода истек';

  public function getTargets()
  {
    return self::CLASS_CONSTRAINT;
  }
}
