<?php

namespace AppBundle\Form\Company;

use AppBundle\Entity\Company\CompanyStatus;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CompanyStatusChoiceType
 * @package AppBundle\Form\Company
 */
class CompanyStatusChoiceType extends ChoiceType
{
  public function configureOptions (OptionsResolver $resolver)
  {
    parent::configureOptions($resolver);
    $resolver->setDefault('choices', array_flip(CompanyStatus::getAvailableName()));
  }
}