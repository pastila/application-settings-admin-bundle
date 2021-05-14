<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Form\Obrashcheniya;

use AppBundle\Entity\Disease\Disease;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Validator\Constraints\NotBlank;

class OmsChargeComplaint4thStepType extends OmsChargeComplaintType
{

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('disease', EntityType::class, [
        'required' => true,
        'class' => Disease::class,
        'constraints' => [
          new NotBlank(['message' => 'Выберите болезнь',]),
        ],
      ]);
  }
}
