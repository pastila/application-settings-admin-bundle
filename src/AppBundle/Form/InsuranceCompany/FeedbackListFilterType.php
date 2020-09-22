<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Form\InsuranceCompany;

use AppBundle\Entity\Company\Company;
use AppBundle\Entity\Geo\Region;
use AppBundle\Form\BezbahilFilterChoiceType;
use AppBundle\Form\BezbahilFilterEntityType;
use AppBundle\Model\InsuranceCompany\FeedbackListFilter;
use AppBundle\Repository\Company\CompanyRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Форма фильтра страховых компаний
 *
 * @package AppBundle\Form\InsuranceCompany
 */
class FeedbackListFilterType extends AbstractType
{
  private $companyRepository;

  public function __construct(CompanyRepository $companyRepository)
  {
    $this->companyRepository = $companyRepository;
  }

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('company', BezbahilFilterEntityType::class, [
        'class' => Company::class,
        'url_builder' => $options['url_builder'],
        'label' => 'Страховая компания <span>( < ?= $countReviews ? > )</span>'
      ])
      ->add('rating', BezbahilFilterChoiceType::class, [
        'choices' => $this->getRatingOptions(),
        'url_builder' => $options['url_builder'],
        'label' => 'Оценка'
      ])
      ->add('region', BezbahilFilterEntityType::class, [
        'class' => Region::class,
        'url_builder' => $options['url_builder'],
        'label' => 'Регион'
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      'data_class' => FeedbackListFilter::class,
      'csrf_protection' => false
    ]);

    $resolver->setRequired('url_builder');
  }

  public function getBlockPrefix()
  {
    return 'filter';
  }

  public function getRatingOptions()
  {
    $values = [];
    for ($i = 1; $i <= 5; $i++)
    {
      $values["Оценки ".$i] = $i;
    }
    return $values;
  }
}
