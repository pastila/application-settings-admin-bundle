<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Form\Feedback;

use AppBundle\Form\Widget\BezbahilAutocompleteCompanyType;
use AppBundle\Form\Widget\BezbahilAutocompleteRegionType;
use AppBundle\Form\Widget\BezbahilRatingType;
use AppBundle\Repository\Company\InsuranceCompanyBranchRepository;
use AppBundle\Repository\Company\InsuranceCompanyRepository;
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

class FeedbackType extends AbstractType
{
  private $companyRepository;
  private $regionRepository;

  public function __construct(InsuranceCompanyRepository $companyRepository, RegionRepository $regionRepository)
  {
    $this->companyRepository = $companyRepository;
    $this->regionRepository = $regionRepository;
  }

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('region', BezbahilAutocompleteRegionType::class, [
        'required' => true,
        'constraints' => [
          new NotBlank(),
        ],
        'query_builder' => function(RegionRepository $repository){
          return $repository->createQueryBuilder('r')->orderBy('r.name');
        },
      ])
      ->add('branch', BezbahilAutocompleteCompanyType::class, [
        'required' => true,
        'constraints' => [
          new NotBlank(),
        ],
        'query_builder' => function(InsuranceCompanyBranchRepository $repository){
          return $repository->createQueryBuilder('b')->leftJoin('b.company', 'c')->orderBy('c.name');
        },
      ])
      ->add('author_name')
      ->add('title', null, [
        'required' => true,
        'constraints' => [
          new NotBlank(),
          new Length([
            'max' => 255,
          ]),
        ],
      ])
      ->add('text', null, [
        'required' => true,
        'constraints' => [
          new NotBlank(),
        ]
      ])
      ->add('valuation', BezbahilRatingType::class, [
        'required' => true,
//в данный момент надо дать возможность любой отзыв написать, хоть и без оценки хоть и по сути не отзыв
//        'constraints' => [
//          new NotBlank(),
//        ],
      ])
      ->add('reviewLetter', HiddenType::class)
    ;
  }

  protected function getCompanyChoices()
  {
    $companies = $this->companyRepository->findBy([], ['name' => 'ASC']);

    $choices = [];
    foreach ($companies as $company)
    {
      $choices[$company->getName()] = $company->getId();
    }

    return $choices;
  }

  protected function getRegionChoices()
  {
    $regions = $this->regionRepository->findBy([], ['name' => 'ASC']);

    $choices = [];
    foreach ($regions as $region)
    {
      $choices[$region->getName()] = $region->getId();
    }

    return $choices;
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefault('data_class', 'AppBundle\Entity\Company\Feedback');
  }
}
