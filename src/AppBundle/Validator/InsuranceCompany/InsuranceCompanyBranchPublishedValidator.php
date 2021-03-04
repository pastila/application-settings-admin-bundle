<?php

namespace AppBundle\Validator\InsuranceCompany;

use AppBundle\Entity\Company\InsuranceCompany;
use AppBundle\Entity\Company\InsuranceCompanyBranch;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class InsuranceCompanyBranchPublishedValidator
 * @package AppBundle\Validator\InsuranceCompany
 */
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
    if (!$constraint instanceof InsuranceCompanyBranchPublished) {
      throw new UnexpectedTypeException($constraint, InsuranceCompanyBranchPublished::class);
    }

    /**
     * @var InsuranceCompanyBranch $feedback
     */
    $branch = $this->context->getObject();
    if (!$branch instanceof InsuranceCompanyBranch) {
      throw new UnexpectedTypeException($branch, InsuranceCompanyBranch::class);
    }

    if ($branch->getPublished() && count($branch->getRepresentatives()) == 0)
    {
      $this->context->addViolation($constraint->message, [
        '{message}' => 'Филиал должен иметь представителей перед публикацией',
      ]);
    }
  }
}