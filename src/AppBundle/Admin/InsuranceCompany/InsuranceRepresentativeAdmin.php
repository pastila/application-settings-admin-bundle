<?php

namespace AppBundle\Admin\InsuranceCompany;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class InsuranceRepresentativeAdmin
 * @package AppBundle\Admin\InsuranceCompany
 */
class InsuranceRepresentativeAdmin extends AbstractAdmin
{
  /**
   * @param FormMapper $form
   */
  protected function configureFormFields(FormMapper $form)
  {
    $form
      ->add('email', null, [
        'required' => true,
        'constraints' => [
          new NotBlank(),
          new Email(),
          new Length([
            'min' => 3,
            'max' => 255,
          ]),
        ],
      ]);
  }
}