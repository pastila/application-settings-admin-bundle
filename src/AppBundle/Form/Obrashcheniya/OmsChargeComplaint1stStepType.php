<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Form\Obrashcheniya;


use AppBundle\Form\Common\YearType;
use AppBundle\Form\Widget\BezbahilRegionType;
use AppBundle\Repository\Geo\RegionRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class OmsChargeComplaint1stStepType extends OmsChargeComplaintType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('region', BezbahilRegionType::class, [
        'required' => true,
        'constraints' => [
          new NotNull(['message' => 'Выберите регион',]),
        ],
        'query_builder' => function (RegionRepository $repository)
        {
          return $repository->createQueryBuilder('r')->orderBy('r.name');
        },
      ])
      ->add('year', YearType::class, [
        'required' => true,
        'constraints' => [
          new NotBlank(['message' => 'Выберите год',]),
        ],
      ]);
  }
}
