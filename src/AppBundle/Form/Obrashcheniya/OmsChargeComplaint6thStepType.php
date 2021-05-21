<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Form\Obrashcheniya;

use AppBundle\Entity\OmsChargeComplaint\OmsChargeComplaint;
use AppBundle\Validator\Constraints\PhoneVerificationRequest;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class OmsChargeComplaint6thStepType extends OmsChargeComplaintType
{
  private $oldPatientPhone;

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('patient', PatientType::class, [
//        'constraints' => [
//          new PhoneVerificationRequest(),
//        ],
      ])
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
    ;

    $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'preSetData']);
    $builder->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'onPreSubmit']);
  }

  public function preSetData (FormEvent $event)
  {
    if ($event->getData())
    {
      /** @var OmsChargeComplaint $omsChargeComplaint */
      $omsChargeComplaint = $event->getData();
      $patient = $omsChargeComplaint->getPatient();
      $this->oldPatientPhone = $patient->getPhone();
    }
  }

  public function onPreSubmit (FormEvent $event)
  {
    if ($event->getData())
    {
      $data = $event->getData();
      $phone = $data['patient']['phone'];

      if ($this->oldPatientPhone != $phone)
      {
        $event->getForm()->remove('patient');
        $event->getForm()->add('patient', PatientType::class, [
          'constraints' => [
            new PhoneVerificationRequest(),
          ],
        ]);
      }
    }
  }
}
