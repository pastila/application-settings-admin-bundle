<?php

namespace AppBundle\Form\Admin\Company;

use AppBundle\Entity\Company\InsuranceCompany;
use AppBundle\Entity\Company\InsuranceCompanyBranch;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InsuranceCompanyBranchAutocompleteType extends EntityType
{
  public function configureOptions (OptionsResolver $resolver)
  {
    parent::configureOptions($resolver);
//    $resolver->setDefault('data_class', InsuranceCompanyBranch::class);
    $resolver->setDefault('class', InsuranceCompanyBranch::class);
    $resolver->setDefault('required', false);
  }

  public function finishView (FormView $view, FormInterface $form, array $options)
  {
    $companies = $this->registry
      ->getRepository(InsuranceCompany::class)
      ->getWithBranchActive()
      ->getQuery()
      ->getResult()
    ;
    parent::finishView($view, $form, $options);
    $view->vars['companies'] = $companies;
    $view->vars['branches'] = [];

    if ($form->getData())
    {
      /** @var InsuranceCompanyBranch $branch */
      $branch = $form->getData();
      $company = $branch->getCompany();
      $view->vars['branches'] = $company->getBranches();
    }
  }

  public function getBlockPrefix ()
  {
    return 'insurance_company_branch_autocomplete';
  }
}