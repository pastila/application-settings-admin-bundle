<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Form\Obrashcheniya;

use AppBundle\Entity\User\Patient;
use AppBundle\Exception\Patient\AmbiguousPatientResolveException;
use AppBundle\Validator\Constraints\PhoneVerificationRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;

class OmsChargeComplaint6thStepType extends OmsChargeComplaintType
{
  private $entityManager;

  public function __construct (EntityManagerInterface $entityManager)
  {
    $this->entityManager = $entityManager;
  }


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

    $builder->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'onPreSubmit']);
    $builder->get('patient')->addEventListener(FormEvents::SUBMIT, [$this, 'onSubmitPatient']);
  }


  public function onPreSubmit (FormEvent $event)
  {
    if ($event->getData())
    {
      $data = $event->getData();
      $patient = $event->getForm()->getData()->getPatient();
      $user = $patient ? $patient->getUser() : null;

      if ($patient instanceof Patient)
      {
        $resolvedPatient = new Patient();
        $resolvedPatient->setLastName($data['patient']['lastName']);
        $resolvedPatient->setFirstName($data['patient']['firstName']);
        $resolvedPatient->setMiddleName($data['patient']['middleName']);
        $resolvedPatient->setInsurancePolicyNumber($data['patient']['insurancePolicyNumber']);
        $existingPatient = $this->entityManager->getRepository('AppBundle:User\Patient')
          ->resolveByPatient($resolvedPatient, $user);

        /*
         * Если это новый пациент или это существующий пациент, но с новым номером, то валидируем проверочный код
         */
        if ($existingPatient === null || $existingPatient->getPhone() != $data['patient']['phone'])
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

  /*
   * Ищем привязанного пациента по полям
   * Если такой есть у пользователя, то возвращаем его с измененными полями вместо создаия нового
   */
  public function onSubmitPatient (FormEvent $event)
  {
    $patient = $event->getData();

    if ($patient instanceof Patient)
    {
      try
      {
        $existingPatient = $this->entityManager
          ->getRepository('AppBundle:User\Patient')
          ->resolveByPatient($patient, $patient->getUser());
      }
      catch (AmbiguousPatientResolveException $e)
      {
      }

      if (isset($existingPatient))
      {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        /** @var Form $item */
        foreach ($event->getForm()->getIterator() as $item)
        {
          $propertyPath = (string)$item->getPropertyPath();
          $propertyAccessor->setValue($existingPatient, $propertyPath, $propertyAccessor->getValue($patient, $propertyPath));
        }

        $event->setData($existingPatient);
      }
    }
  }
}
