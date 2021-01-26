<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Form\Widget;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BezbahilAutocompleteCompanyType extends EntityType
{
  public function __construct(RegistryInterface $registry)
  {
    parent::__construct($registry);
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefault('class', 'AppBundle\Entity\Company\InsuranceCompanyBranch');
    parent::configureOptions($resolver);
  }

  public function getBlockPrefix()
  {
    return 'bezbahil_autocomplete_company';
  }
}
