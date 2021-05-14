<?php

namespace AppBundle\Controller\Disease;

use AppBundle\Form\Common\SuggestType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DiseaseController extends Controller
{
  /**
   * @Route(name="disease_suggest", path="/diseases/suggest", methods={"GET"})
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
    $data = [];

    foreach ($diseases as $disease)
    {
      $data[] = [
        'value' => $disease->getName(),
        'data' => $disease->getId(),
      ];
    }

    return $this->json($data);
  }
}