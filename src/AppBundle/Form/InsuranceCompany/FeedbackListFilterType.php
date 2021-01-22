<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Form\InsuranceCompany;

use AppBundle\Entity\Company\Company;
use AppBundle\Entity\Company\CompanyBranch;
use AppBundle\Entity\Company\CompanyStatus;
use AppBundle\Entity\Company\FeedbackModerationStatus;
use AppBundle\Entity\Geo\Region;
use AppBundle\Form\BezbahilFilterChoiceType;
use AppBundle\Form\BezbahilFilterEntityType;
use AppBundle\Model\InsuranceCompany\FeedbackListFilter;
use AppBundle\Repository\Company\CompanyRepository;
use Doctrine\ORM\EntityRepository;
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
        'query_builder' => function () {
          return $this->companyRepository->createChoiceListQueryBuilder();
        },
        'url_builder' => $options['url_builder'],
        'label' => 'Страховая компания <span>('.$this->companyRepository->countActiveAll().')</span>',
        'clear_label' => 'Очистить отзывы'
      ])
      ->add('rating', BezbahilFilterChoiceType::class, [
        'choices' => $this->getRatingOptions(),
        'url_builder' => $options['url_builder'],
        'label' => 'Оценка',
        'clear_label' => 'Очистить оценку'
      ])
      ->add('region', BezbahilFilterEntityType::class, [
        'class' => Region::class,
        'query_builder' => function (EntityRepository $er) {
          return $er->createQueryBuilder('u')
            ->orderBy('u.name', 'ASC');
        },
        'url_builder' => $options['url_builder'],
        'label' => 'Регион',
        'clear_label' => 'Очистить регион'
      ])
      ->add('moderation', ChoiceType::class, [
        'choices' => FeedbackModerationStatus::getAvailableValues()
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
