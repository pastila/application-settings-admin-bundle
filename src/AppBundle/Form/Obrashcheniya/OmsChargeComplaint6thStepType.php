<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Form\Obrashcheniya;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class OmsChargeComplaint6thStepType extends OmsChargeComplaintType
{

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('patient', PatientType::class)
      ->add('paidAt', DateType::class, [
        'required' => true,
        'widget' => 'single_text',
        'constraints' => [
          new NotBlank(['message' => 'Укажите дату оплаты услуги',]),
        ],
      ])
      ->add('documents', OmsChargeComplaintDocumentType::class, [
        'required' => false,
        'multiple' => true,
      ])
      ->add('verifySmsCode', TextType::class, [
        'required' => true,
        'mapped' => false,
      ])
    ;
  }
}
