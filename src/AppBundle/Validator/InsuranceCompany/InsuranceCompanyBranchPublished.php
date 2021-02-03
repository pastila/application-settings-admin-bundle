<?php

namespace AppBundle\Validator\InsuranceCompany;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class InsuranceCompanyBranchPublished extends Constraint
{
  public $message = '{message}';

  public function getTargets()
  {
    return self::CLASS_CONSTRAINT;
  }
}