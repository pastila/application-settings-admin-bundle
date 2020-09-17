<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReviewsController
{
  /**
   * @Route(path="/reviews")
   */
  public function indexAction()
  {
    return new Response('FOO');
  }
}
