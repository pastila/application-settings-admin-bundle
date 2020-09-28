<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Form\Feedback;

use AppBundle\Form\Widget\BezbahilAutocompleteCompanyType;
use AppBundle\Form\Widget\BezbahilAutocompleteRegionType;
use AppBundle\Form\Widget\BezbahilRatingType;
use AppBundle\Repository\Company\CompanyRepository;
use AppBundle\Repository\Geo\RegionRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class FeedbackType extends AbstractType
{
  private $companyRepository;
  private $regionRepository;

  public function __construct(CompanyRepository $companyRepository, RegionRepository $regionRepository)
  {
    $this->companyRepository = $companyRepository;
    $this->regionRepository = $regionRepository;
  }

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('region', BezbahilAutocompleteRegionType::class, [
        'choices' => $this->getRegionChoices(),
      ])
      ->add('branch', BezbahilAutocompleteCompanyType::class, [
        'choices' => $this->getCompanyChoices()
      ])
      ->add('author_name')
      ->add('title')
      ->add('text')
      ->add('valuation', BezbahilRatingType::class)
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
