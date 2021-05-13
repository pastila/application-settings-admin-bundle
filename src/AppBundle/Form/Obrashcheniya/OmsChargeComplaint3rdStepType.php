<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Form\Obrashcheniya;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class OmsChargeComplaint3rdStepType extends OmsChargeComplaintType
{
//  private $regionRepository;
//
//  public function __construct(RegionRepository $regionRepository)
//  {
//    $this->regionRepository = $regionRepository;
//  }

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('urgent', ChoiceType::class, [
        'expanded' => true,
        'required' => true,
        'choices' => [
          'Плановое' => '0',
          'Неотложное' => '1',
        ],
      ]);
  }
}
