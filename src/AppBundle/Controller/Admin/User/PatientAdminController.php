<?php

namespace AppBundle\Controller\Admin\User;

use AppBundle\Entity\User\Patient;
use AppBundle\Model\InsuranceCompany\OmsChargeComplaintFilter;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PatientAdminController extends CRUDController
{
  /**
   * @param Request $request
   * @param Patient $object
   * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response|null
   */
  protected function preDelete (Request $request, $object)
  {
    $filter = new OmsChargeComplaintFilter();
    $filter->setPatient($object);
    $nbAppeals = $this->getDoctrine()->getRepository('AppBundle:OmsChargeComplaint\OmsChargeComplaint')->countByFilter($filter);

    if ($nbAppeals > 0)
    {
      $this->addFlash('error', 'У пациента есть привязанные обращения. Удаление невозможно.');
      return $this->redirectToList();
    }
  }

  public function batchActionDelete (ProxyQueryInterface $query)
  {
    $queryProxy = clone $query;
    $queryProxy->select('DISTINCT ' . current($queryProxy->getRootAliases()));

    foreach ($queryProxy->getQuery()->iterate() as $pos => $object)
    {
      /** @var Patient $patient */
      $patient = $object[0];
      $filter = new OmsChargeComplaintFilter();
      $filter->setPatient($patient);
      $nbAppeals = $this->getDoctrine()->getRepository('AppBundle:OmsChargeComplaint\OmsChargeComplaint')->countByFilter($filter);

      if ($nbAppeals > 0)
      {
        $this->addFlash('error', sprintf('У пациента %s есть привязанные обращения. Удаление невозможно.', $patient->getFio()));

        $query->andWhere(sprintf('o != :patient_%s', $pos));
        $query->setParameter(sprintf('patient_%s', $pos), $patient);
      }
    }

    return parent::batchActionDelete($query);
  }

  /**
   * @param Request $request
   * @param Patient $patient
   * @return \Symfony\Component\HttpFoundation\Response|void|null
   */
  protected function preEdit (Request $request, $patient)
  {
    if (!$this->admin->isChild())
    {
      $user = $patient->getUser();

      if ($user !== null)
      {
        return $this->redirectToRoute('admin_app_user_user_user_patient_edit', [
          'id' => $user->getId(),
          'childId' => $patient->getId(),
        ]);
      }
    }
  }

  protected function preList (Request $request)
  {
    if (!$this->admin->isChild())
    {
      return $this->redirectToRoute('admin_app_user_user_user_patient_edit');
    }
  }

  /**
   * @param Request $request
   */
  public function setMainAction (Request $request)
  {
    /** @var Patient $patient */
    $patient = $this->admin->getSubject();

    if ($patient === null)
    {
      throw new NotFoundHttpException();
    }

    if ($patient->getUser() === null)
    {
      throw new NotFoundHttpException();
    }

    $patient->getUser()->setMainPatient($patient);
    $this->getDoctrine()->getManager()->persist($patient);
    $this->getDoctrine()->getManager()->flush();
    return $this->redirectToList();
  }

}