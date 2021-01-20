<?php

namespace AppBundle\Form\Organization;

use AppBundle\Entity\Organization\OrganizationStatus;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class OrganizationStatusChoiceType
 * @package AppBundle\Form\Organization
 */
class OrganizationStatusChoiceType extends ChoiceType
{
  public function configureOptions (OptionsResolver $resolver)
  {
    parent::configureOptions($resolver);
    $resolver->setDefault('choices', array_flip(OrganizationStatus::getAvailableName()));
  }
}