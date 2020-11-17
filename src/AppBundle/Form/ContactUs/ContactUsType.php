<?php

namespace AppBundle\Form\ContactUs;

use AppBundle\Form\Widget\BezbahilAutocompleteCompanyType;
use AppBundle\Form\Widget\BezbahilAutocompleteRegionType;
use AppBundle\Form\Widget\BezbahilRatingType;
use AppBundle\Repository\Company\CompanyBranchRepository;
use AppBundle\Repository\Company\CompanyRepository;
use AppBundle\Repository\Geo\RegionRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class ContactUsType extends AbstractType
{
  public function __construct()
  {
  }

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('author_name')
      ->add('email', null, [
        'required' => true,
        'constraints' => [
          new NotBlank(),
          new Length([
            'max' => 255,
          ]),
        ],
      ])
      ->add('message', null, [
        'required' => true,
        'constraints' => [
          new NotBlank(),
        ]
      ]);
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefault('data_class', 'AppBundle\Entity\ContactUs\ContactUs');
  }
}
