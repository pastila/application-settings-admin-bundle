<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Form\Feedback;

use AppBundle\Form\Widget\BezbahilAutocompleteType;
use AppBundle\Form\Widget\BezbahilRatingType;
use AppBundle\Repository\Company\CompanyRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\SubmitButton;

class FeedbackType extends AbstractType
{
  private $companyRepository;

  public function __construct(CompanyRepository $companyRepository)
  {
    $this->companyRepository = $companyRepository;
  }

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('region', BezbahilAutocompleteType::class, [
        'choices' => $this->getCompanyChoices()
      ])
      ->add('company', BezbahilAutocompleteType::class, [
        'choices' => []
      ])
      ->add('author_name')
      ->add('title')
      ->add('text')
      ->add('valuation', BezbahilRatingType::class)
      ->add('submit', SubmitType::class);
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
}
