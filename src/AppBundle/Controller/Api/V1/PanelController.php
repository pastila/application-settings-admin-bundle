<?php

namespace AppBundle\Controller\Api\V1;

use AppBundle\Entity\User\User;
use AppBundle\Repository\Company\FeedbackRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PanelController
 * @package AppBundle\Controller\Api\V1
 */
class PanelController extends Controller
{
  /**
   * @var FeedbackRepository
   */
  private $feedbackRepository;

  /**
   * PanelController constructor.
   * @param FeedbackRepository $feedbackRepository
   */
  public function __construct(
    FeedbackRepository $feedbackRepository
  )
  {
    $this->feedbackRepository = $feedbackRepository;
  }

  /**
   * @Route("/api/v1/panel", name="api_panel", methods={"GET"}, requirements={ "user": "\d+" })
   */
  public function getPanelAction(Request $request)
  {
    $login = $request->get('user');
    if (empty($login))
    {
      return new Response(null);
    }

    /**
     * @var User $user
     */
    $user = $this->getDoctrine()->getManager()->getRepository(User::class)
      ->findOneBy(['login' => $login]);
    if (!$user)
    {
      return new Response(null);
    }
    $nbReviews = $this->feedbackRepository
      ->countUserReviews($user);

    return new Response($nbReviews);
  }
}
