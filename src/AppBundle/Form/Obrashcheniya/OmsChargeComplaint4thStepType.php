<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Form\Obrashcheniya;

use AppBundle\Entity\Disease\Disease;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotNull;

class OmsChargeComplaint4thStepType extends OmsChargeComplaintType
{

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('disease', EntityType::class, [
        'required' => true,
        'class' => Disease::class,
        'constraints' => [
          new NotNull(['message' => 'Выберите болезнь',]),
        ],
      ]);
  }
}
