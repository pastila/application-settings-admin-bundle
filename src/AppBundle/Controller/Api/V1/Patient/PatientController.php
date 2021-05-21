<?php

namespace AppBundle\Controller\Api\V1\Patient;

use AppBundle\Exception\PhoneVerificationRequestManagerException;
use AppBundle\Form\Obrashcheniya\PatientFormVerifyType;
use AppBundle\Form\Patient\PatientFilterType;
use AppBundle\Model\Filter\PatientFilter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PatientController extends Controller
{
  /**
   * @param Request $request
   * @Route(name="api_patient_sms_verify", path="/api/v1/phone-verify", methods={"POST"})
   */
  public function sendSmsVerifyCodeAction (Request $request)
  {
    $form = $this->createForm(PatientFormVerifyType::class, null, [
      'csrf_protection' => false,
    ]);
    $form->submit(json_decode($request->getContent(), true));

    if (!$form->isValid())
    {
      return $this->json($this->get('aw.client_application.transformer')->getClientModelData($form, 'form.errors'), 400);
    }

    $requestManager = $this->get('AppBundle\Service\Registration\PhoneVerification\PhoneVerificationRequestManager');

    try
    {
      $requestManager->sendVerificationCode($requestManager->createVerificationRequest($form->get('phone')->getData()));
    }
    catch (PhoneVerificationRequestManagerException $e)
    {
      return $this->json(['errors' => [
        '#' => $e->getMessage(),
      ]], 500);
    }

    return $this->json(null);
  }

  /**
   * @Route(name="api_patients", path="/api/v1/patients", methods={"GET"})
   * @param Request $request
   */
  public function suggestAction (Request $request)
  {
    $filter = new PatientFilter();
    $filter->setUser($this->getUser());
    $form = $this->createForm(PatientFilterType::class, $filter, [
      'csrf_protection' => false,
    ]);
    $form->submit($request->query->all());

    if ($form->isValid() === false)
    {
      return $this->json($this->get('aw.client_application.transformer')->getClientModelData($form, 'form.errors'), 400);
    }

    $patients = $this->getDoctrine()->getRepository('AppBundle:User\Patient')->findByFilter($filter);
    return $this->json($this->get('aw.client_application.transformer')->getClientModelCollectionData($patients, 'patient'));
  }
}