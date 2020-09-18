<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Controller\InsuranceCompany;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class FeedbackController extends Controller
{
  /**
   * @Route(path="/reviews")
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

    return $this->render('InsuranceCompany/Review/list.html.twig', [
      'reviews' => [],
      'nbReviews' => 0
    ]);
  }
}
