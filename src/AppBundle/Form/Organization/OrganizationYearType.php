<?php

namespace AppBundle\Form\Organization;

use AppBundle\Entity\Organization\OrganizationStatus;
use AppBundle\Helper\Year\Year;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class OrganizationYearType
 * @package AppBundle\Form\Organization
 */
class OrganizationYearType extends ChoiceType
{
//  private $companyRepository;
//
//  public function __construct(EntityRepository $companyRepository)
//  {
//    $this->companyRepository = $companyRepository;
//  }

  public function configureOptions (OptionsResolver $resolver)
  {
    parent::configureOptions($resolver);
    $resolver->setDefault('choices', Year::getYears());
  }
}