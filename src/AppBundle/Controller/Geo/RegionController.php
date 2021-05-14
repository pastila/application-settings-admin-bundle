<?php

namespace AppBundle\Controller\Geo;

use AppBundle\Form\Common\SuggestType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RegionController extends Controller
{
  /**
   * @Route(name="region_autocomplete", path="/regions/suggest", methods={"GET"})
   * @param Request $request
   */
  public function autocompleteAction (Request $request)
  {
    $form = $this->createForm(SuggestType::class, null, [
      'csrf_protection' => false,
    ]);
    $form->submit($request->query->all());
    $query = $form->get('query')->getData();
    $regions = $this->getDoctrine()
      ->getRepository('AppBundle:Geo\Region')
      ->findByQuery($query);
    $data = [];

    foreach ($regions as $region)
    {
      $data[] = [
        'value' => $region->getName(),
        'data' => $region->getId(),
      ];
    }

    return $this->json($data);
  }
}