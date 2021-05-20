<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Obrashcheniya\ObrashcheniyaFile;
use AppBundle\Entity\OmsChargeComplaint\OmsChargeComplaint;
use AppBundle\Form\Obrashcheniya\OmsChargeComplaint1stStepType;
use AppBundle\Service\Obrashcheniya\OmsChargeComplaintSessionPersister;
use AppBundle\Service\Obrashcheniya\OmsChargeComplaintSessionResolver;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class AppealController extends Controller
{
  const DRAFT_STEP_ROUTE_NAMES = [
    'oms_charge_complaint_index',
    'oms_charge_complaint_2nd_step'
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
   * @Route("/forma-obrasheniya/")
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
      'form' => $form->createView()
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

    $form = $this->createForm(OmsChargeComplaint1stStepType::class, $complaintDraft);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid())
    {
      $em = $this->getDoctrine()->getManager();

      $em->persist($form->getData());
      $em->flush();

      $this->omsChargeComplaintSessionPersister->persist($complaintDraft);

      return $this->redirect('oms_charge_complaint_3rd_step');
    }

    return $this->render('AppBundle:OmsChargeComplaint:step2.html.twig', [
      'form' => $form->createView()
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
      if (isset(self::DRAFT_STEP_ROUTE_NAMES[$step-1]))
      {
        return $this->redirectToRoute(self::DRAFT_STEP_ROUTE_NAMES[$step-1]);
      }

      // Если такого шага нет, вернем на первый шаг
      return $this->redirectToRoute(self::DRAFT_STEP_ROUTE_NAMES[0]);
    }

    return null;
  }
}
