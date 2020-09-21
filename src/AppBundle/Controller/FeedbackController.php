<?php

namespace AppBundle\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeedbackController
{
  /**
   * @Route(path="/feedback")
   */
  public function indexAction()
  {
    return new Response('feedback');
  }

  /**
   * @Route(path="/add-feedback")
   */
  public function indexAdd()
  {
    return new Response('add-feedback');
  }
}
