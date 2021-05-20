<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Form\Obrashcheniya;


use AppBundle\Form\Widget\BezbahilRegionType;
use AppBundle\Helper\Year\Year;
use AppBundle\Repository\Geo\RegionRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class OmsChargeComplaint1stStepType extends OmsChargeComplaintType
{
//  private $regionRepository;
//
//  public function __construct(RegionRepository $regionRepository)
//  {
//    $this->regionRepository = $regionRepository;
//  }

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $years = Year::getChoicesYear();

    $builder
      ->add('region', BezbahilRegionType::class, [
        'required' => true,
        'query_builder' => function (RegionRepository $repository)
        {
          return $repository->createQueryBuilder('r')->orderBy('r.name');
        },
      ])
      ->add('year', ChoiceType::class, [
        'required' => true,

        'choices' => $years,
        'data' => (count($years) - 1)
      ]);
  }
}
