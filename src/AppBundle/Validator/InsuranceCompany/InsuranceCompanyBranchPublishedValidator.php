<?php

namespace AppBundle\Validator\InsuranceCompany;

use AppBundle\Entity\Company\InsuranceCompany;
use AppBundle\Entity\Company\InsuranceCompanyBranch;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class InsuranceCompanyBranchPublishedValidator extends ConstraintValidator
{
  /**
   * @param InsuranceCompany $value
   * @param Constraint $constraint
   */
  public function validate($value, Constraint $constraint)
  {
    if (is_null($value))
    {
      return;
    }
    foreach ($value->getBranches() as $branch)
    {
      /**
       * @var InsuranceCompanyBranch $branch
       */
      if ($branch->getPublished() && count($branch->getRepresentatives()) == 0)
      {
        $this->context->addViolation($constraint->message, [
          '{message}' => sprintf('Филиал в регионе "%s" должен иметь представителей перед публикацией', $branch->getRegion()->getName()),
        ]);
      }
    }
  }
}