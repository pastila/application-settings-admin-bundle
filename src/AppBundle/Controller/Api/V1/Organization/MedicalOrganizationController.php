<?php

namespace AppBundle\Controller\Api\V1\Organization;

use AppBundle\Form\Organization\MedicalOrganizationFilterType;
use AppBundle\Model\Filter\MedicalOrganizationFilter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MedicalOrganizationController extends Controller
{
  /**
   * @Route(name="api_medical_organizations", methods={"GET"}, path="/api/v1/medical-organizations")
   * @param Request $request
   */
  public function suggestAction (Request $request)
  {
    $filter = new MedicalOrganizationFilter();
    $form = $this->createForm(MedicalOrganizationFilterType::class, $filter, [
      'csrf_protection' => false,
    ]);
    $form->submit($request->query->all());
    $organizations = $this->getDoctrine()->getRepository('AppBundle:Organization\MedicalOrganization')
      ->findByFilter($filter);

    return $this->json($this->get('aw.client_application.transformer')->getClientModelCollectionData($organizations, 'medical_organization'));
  }
}