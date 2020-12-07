<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Common\News;
use AppBundle\Entity\Question\Question;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class QuestionsController extends Controller
{
  /**
   * @Route("/vopros-otvet", name="questions")
   */
  public function indexAction(Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    $questions = $em->getRepository(Question::class)
      ->getQueryAllSortByPosition()
      ->getQuery()
      ->getResult();

    return $this->render('Questions/list.html.twig', [
      'questions' => $questions
    ]);
  }
}
