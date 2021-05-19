<?php

namespace AppBundle\Controller\Api\V1\Disease;

use AppBundle\Form\Common\SuggestType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DiseaseController extends Controller
{
  /**
   * @Route(name="api_disease", path="/api/v1/diseases", methods={"GET"})
   * @param Request $request
   */
  public function suggestAction (Request $request)
  {
    $form = $this->createForm(SuggestType::class, null, [
      'csrf_protection' => false,
    ]);
    $form->submit($request->query->all());
    $query = $form->get('query')->getData();
    $diseases = $this->getDoctrine()->getRepository('AppBundle:Disease\Disease')->findByQuery($query);

    return $this->json($this->get('aw.client_application.transformer')->getClientModelCollectionData($diseases, 'disease'));
  }
}