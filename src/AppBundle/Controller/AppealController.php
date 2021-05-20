<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Obrashcheniya\ObrashcheniyaFile;
use AppBundle\Entity\OmsChargeComplaint\OmsChargeComplaint;
use AppBundle\Entity\User\Patient;
use AppBundle\Exception\Patient\AmbiguousPatientResolveException;
use AppBundle\Form\Obrashcheniya\OmsChargeComplaint1stStepType;
use AppBundle\Form\Obrashcheniya\OmsChargeComplaint2ndStepType;
use AppBundle\Form\Obrashcheniya\OmsChargeComplaint3rdStepType;
use AppBundle\Form\Obrashcheniya\OmsChargeComplaint4thStepType;
use AppBundle\Form\Obrashcheniya\OmsChargeComplaint6thStepType;
use AppBundle\Form\Obrashcheniya\OmsChargeComplaintChoosePatientType;
use AppBundle\Service\Obrashcheniya\OmsChargeComplaintSessionPersister;
use AppBundle\Service\Obrashcheniya\OmsChargeComplaintSessionResolver;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\Annotation\Route;

class AppealController extends Controller
{
  const DRAFT_STEP_ROUTE_NAMES = [
    1 => 'oms_charge_complaint_index',
    'oms_charge_complaint_2nd_step',
    'oms_charge_complaint_3rd_step',
    'oms_charge_complaint_4th_step',
    'oms_charge_complaint_5th_step',
    'oms_charge_complaint_6th_step',
  ];

  private $omsChargeComplaintSessionResolver;

  private $omsChargeComplaintSessionPersister;

  public function __construct(
    OmsChargeComplaintSessionResolver $omsChargeComplaintSessionResolver,
    OmsChargeComplaintSessionPersister $omsChargeComplaintSessionPersister
  )
  {
    $this->omsChargeComplaintSessionResolver = $omsChargeComplaintSessionResolver;
    $this->omsChargeComplaintSessionPersister = $omsChargeComplaintSessionPersister;
  }

  /**
   * @Route("/forma-obrashenija/")
   */
  public function indexLegacyAction()
  {
    return $this->redirectToRoute('oms_charge_complaint_index', [], 301);
  }

  /**
   * @Route("/oms-charge-complaint", name="oms_charge_complaint_index")
   */
  public function indexAction(Request $request)
  {
    $complaintDraft = $this->omsChargeComplaintSessionResolver->resolve();
    $form = $this->createForm(OmsChargeComplaint1stStepType::class, $complaintDraft);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid())
    {
      $complaintDraft->setDraftStep(2);
      $em = $this->getDoctrine()->getManager();
      $em->persist($form->getData());
      $em->flush();
      $this->omsChargeComplaintSessionPersister->persist($complaintDraft);

      return $this->redirectToRoute('oms_charge_complaint_2nd_step');
    }

    return $this->render('AppBundle:OmsChargeComplaint:index.html.twig', [
      'form' => $form->createView(),
      'complaintDraft' => $complaintDraft,
    ]);
  }

  /**
   * @Route("/oms-charge-complaint/step-2", name="oms_charge_complaint_2nd_step")
   */
  public function secondStepAction(Request $request)
  {
    $complaintDraft = $this->omsChargeComplaintSessionResolver->resolve();
    $response = $this->validateDraftStep($complaintDraft, 2);

    if ($response)
    {
      return $response;
    }

    $form = $this->createForm(OmsChargeComplaint2ndStepType::class, $complaintDraft, [
      'year' => $complaintDraft->getYear(),
      'region' => $complaintDraft->getRegion(),
    ]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid())
    {
      $complaintDraft->setDraftStep(3);
      $em = $this->getDoctrine()->getManager();

      $em->persist($form->getData());
      $em->flush();

      $this->omsChargeComplaintSessionPersister->persist($complaintDraft);

      return $this->redirect($this->generateUrl('oms_charge_complaint_3rd_step'));
    }

    return $this->render('AppBundle:OmsChargeComplaint:step2.html.twig', [
      'form' => $form->createView(),
      'complaintDraft' => $complaintDraft,
    ]);
  }

  /**
   * @Route("/oms-charge-complaint/step-3", name="oms_charge_complaint_3rd_step")
   */
  public function thirdStepAction(Request $request)
  {
    $complaintDraft = $this->omsChargeComplaintSessionResolver->resolve();
    $response = $this->validateDraftStep($complaintDraft, 3);

    if ($response)
    {
      return $response;
    }

    $form = $this->createForm(OmsChargeComplaint3rdStepType::class, $complaintDraft);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid())
    {
      $complaintDraft->setDraftStep(4);
      $em = $this->getDoctrine()->getManager();

      $em->persist($form->getData());
      $em->flush();

      $this->omsChargeComplaintSessionPersister->persist($complaintDraft);

      return $this->redirect($this->generateUrl('oms_charge_complaint_4th_step'));
    }

    return $this->render('AppBundle:OmsChargeComplaint:step3.html.twig', [
      'form' => $form->createView(),
      'complaintDraft' => $complaintDraft,
    ]);
  }

  /**
   * @Route("/oms-charge-complaint/step-4", name="oms_charge_complaint_4th_step")
   */
  public function fourthStepAction(Request $request)
  {
    $complaintDraft = $this->omsChargeComplaintSessionResolver->resolve();
    $response = $this->validateDraftStep($complaintDraft, 4);

    if ($response)
    {
      return $response;
    }

    $form = $this->createForm(OmsChargeComplaint4thStepType::class, $complaintDraft);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid())
    {
      $complaintDraft->setDraftStep(5);
      $em = $this->getDoctrine()->getManager();

      $em->persist($form->getData());
      $em->flush();

      $this->omsChargeComplaintSessionPersister->persist($complaintDraft);

      return $this->redirect($this->generateUrl('oms_charge_complaint_5th_step'));
    }

    return $this->render('AppBundle:OmsChargeComplaint:step4.html.twig', [
      'form' => $form->createView(),
      'complaintDraft' => $complaintDraft,
    ]);
  }

  /**
   * @Route("/oms-charge-complaint/step-5", name="oms_charge_complaint_5th_step")
   */
  public function fifthStepAction(Request $request)
  {
    $complaintDraft = $this->omsChargeComplaintSessionResolver->resolve();
    $response = $this->validateDraftStep($complaintDraft, 5);

    if ($response)
    {
      return $response;
    }

    if ($this->isGranted('ROLE_USER'))
    {
      $complaintDraft->setDraftStep(6);
      $em = $this->getDoctrine()->getManager();
      $em->persist($complaintDraft);
      $em->flush();
      $this->omsChargeComplaintSessionPersister->persist($complaintDraft);

      return $this->render('@App/OmsChargeComplaint/step5.html.twig', [
        'complaintDraft' => $complaintDraft,
      ]);
    }

    $csrfToken = $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue();
    $lastUsername = $request->getSession()->get('_security.last_username');
    $lastError = $request->getSession()->get('_security.last_error');
    $request->getSession()->remove('_security.last_error');

    return $this->render('AppBundle:OmsChargeComplaint:step5_login.html.twig', [
      'complaintDraft' => $complaintDraft,
      'csrfToken' => $csrfToken,
      'lastUsername' => $lastUsername,
      'lastError' => $lastError,
    ]);
  }

  /**
   * @Route(name="oms_charge_complaint_6th_step", path="oms-charge-complaint/step-6")
   */
  public function sixthStepAction (Request $request)
  {
    $complaintDraft = $this->omsChargeComplaintSessionResolver->resolve();
    $response = $this->validateDraftStep($complaintDraft, 6);

    if ($response)
    {
      return $response;
    }

    $patient = new Patient();
    $patient->setUser($this->getUser());
    $complaintDraft->setPatient($patient);
    $form = $this->createForm(OmsChargeComplaint6thStepType::class, $complaintDraft);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid())
    {
      try
      {
        $existingPatient = $this->getDoctrine()->getRepository('AppBundle:User\Patient')->resolveByPatient($patient);
      }
      catch (AmbiguousPatientResolveException $e)
      {
      }

      if ($existingPatient !== null)
      {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        /** @var Form $item */
        foreach ($form->get('patient')->getIterator() as $item)
        {
          $propertyPath = (string)$item->getPropertyPath();
          $propertyAccessor->setValue($existingPatient, $propertyPath, $propertyAccessor->getValue($patient, $propertyPath));
        }

        $complaintDraft->setPatient($existingPatient);
      }

      $complaintDraft->setDraftStep(7);
      $em = $this->getDoctrine()->getManager();

      $em->persist($complaintDraft);
      $em->flush();

      $this->omsChargeComplaintSessionPersister->persist($complaintDraft);

      return $this->redirect($this->generateUrl('oms_charge_complaint_7th_step'));
    }

    return $this->render('AppBundle:OmsChargeComplaint:step6.html.twig', [
      'complaintDraft' => $complaintDraft,
      'form' => $form->createView(),
    ]);
  }

  /**
   * @Route(name="oms_charge_complaint_7th_step", path="oms-charge-complaint/step-7")
   */
  public function seventhStepAction (Request $request)
  {
    $complaintDraft = $this->omsChargeComplaintSessionResolver->resolve();
    $response = $this->validateDraftStep($complaintDraft, 7);

    if ($response)
    {
      return $response;
    }

    $complaintDraft->setStatus(OmsChargeComplaint::STATUS_CREATED);
    $em = $this->getDoctrine()->getManager();

    $em->persist($complaintDraft);
    $em->flush();

    $this->omsChargeComplaintSessionPersister->persist($complaintDraft);
    $this->omsChargeComplaintSessionPersister->reset();

    return $this->render('AppBundle:OmsChargeComplaint:step7.html.twig', [
      'complaintDraft' => $complaintDraft,
    ]);
  }

  /**
   * @Route(name="not_insurance_case", methods={"GET"}, path="/oms-charge-complaint/not-insurance-case")
   * @param Request $request
   */
  public function notInsuranceCaseAction (Request $request)
  {
    $complaintDraft = $this->omsChargeComplaintSessionResolver->resolve();

    return $this->render('@App/OmsChargeComplaint/not_insurance_case.html.twig', [
      'complaintDraft' => $complaintDraft,
      'backUrl' => $request->get('backUrl'),
    ]);
  }

  /**
   * @Route("/appeals/{id}/download", name="appeal_download")
   */
  public function appealDownloadAction(Request $request)
  {
    $this->denyAccessUnlessGranted(['ROLE_ADMIN', 'ROLE_USER']);

    $user = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $obrashcheniyaFile = $em->getRepository(ObrashcheniyaFile::class)
      ->createFileQueryBuilder($request->get('id'), $request->get('image_number'), ($user->getIsAdmin() ? null : $user))
      ->setMaxResults(1)
      ->getQuery()
      ->getOneOrNullResult();

    if (!$obrashcheniyaFile)
    {
      throw $this->createNotFoundException(sprintf('Obrashcheniya File model not found'));
    }

    try
    {
      $response = new BinaryFileResponse($obrashcheniyaFile->getFile());
      $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_INLINE, $obrashcheniyaFile->getFileName());
    }
    catch (FileNotFoundException $e)
    {
      throw $this->createNotFoundException(sprintf('Obrashcheniya file not found'));
    }

    return $response;
  }

  /**
   * Если текущий шаг меньше запрошенного (т.е. до запрошенного еще не дошли)
   * то надо возвращать на правильный шаг
   *
   * Шаги начинаются с 1
   *
   * @param OmsChargeComplaint $omsChargeComplaint
   * @param $step
   * @return RedirectResponse|null
   */
  public function validateDraftStep(OmsChargeComplaint $omsChargeComplaint, $step)
  {
    if ($omsChargeComplaint->getDraftStep() < $step)
    {
      if (isset(self::DRAFT_STEP_ROUTE_NAMES[$step - 1]))
      {
        return $this->redirectToRoute(self::DRAFT_STEP_ROUTE_NAMES[$step - 1]);
      }

      // Если такого шага нет, вернем на первый шаг
      return $this->redirectToRoute(self::DRAFT_STEP_ROUTE_NAMES[0]);
    }

    return null;
  }
}
