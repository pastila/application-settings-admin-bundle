<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Controller\InsuranceCompany;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeedbackController extends Controller
{
  /**
   * @Route(path="/feedback")
   */
  public function indexAction()
  {
//      $qb = $this
//        ->getDoctrine()
//        ->getManager()
//        ->getRepository('AppBundle:Company\CompanyFeedback')
//        ->createQueryBuilder('fb');
//
//      $paginator = new Paginator($qb);

    $companies = $this->getDoctrine()->getRepository('AppBundle:Company\Company')->findAll();


    return $this->render('InsuranceCompany/Review/list.html.twig', [
      'reviews' => [],
      'nbReviews' => 0
    ]);
  }

  /**
   * @Route(path="/add-feedback")
   */
  public function indexAdd()
  {
    return new Response('add-feedback');
  }
}
