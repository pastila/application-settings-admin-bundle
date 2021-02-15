<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Form\Widget;


use AppBundle\Entity\Geo\Region;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InsuranceCompanyBranchRegionType extends AbstractType
{
  /**
   * @var EntityManagerInterface
   */
  private $entityManager;

  /**
   * InsuranceCompanyBranchRegionType constructor.
   * @param EntityManagerInterface $entityManager
   */
  public function __construct(
    EntityManagerInterface $entityManager
  )
  {
    $this->entityManager = $entityManager;
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefault('subject', null);
    parent::configureOptions($resolver);
  }

  public function buildView (FormView $view, FormInterface $form, array $options)
  {
    $regions = $this->entityManager->getRepository(Region::class)
      ->createQueryBuilder('r')
      ->orderBy('r.name', 'ASC')
      ->getQuery()
      ->getResult();

    $view->vars['subject'] = $options['subject'];
    $view->vars['regions'] = $regions;
  }

  public function getBlockPrefix()
  {
    return 'insurance_company_branch_region';
  }
}
