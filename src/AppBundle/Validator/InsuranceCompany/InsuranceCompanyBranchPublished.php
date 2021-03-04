<?php

namespace AppBundle\Validator\InsuranceCompany;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class InsuranceCompanyBranchPublished
 * @package AppBundle\Validator\InsuranceCompany
 * @Annotation
 */
class InsuranceCompanyBranchPublished extends Constraint
{
  public $message = '{message}';

  public function getTargets()
  {
    return [self::PROPERTY_CONSTRAINT];
  }
}