<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Number\Number;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends Controller
{
  /**
   * @Route("/", name="homepage")
   */
  public function indexAction(Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    $numbers = $em->getRepository(Number::class)
      ->getAll()
      ->getQuery()
      ->getResult();

    // replace this example code with whatever you need
    return $this->render('@App/homepage.html.twig', [
      'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
      'numbers' => $numbers
    ]);
  }
}