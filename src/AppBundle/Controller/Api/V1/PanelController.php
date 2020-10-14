<?php

namespace AppBundle\Controller\Api\V1;

use AppBundle\Entity\User\User;
use AppBundle\Repository\Company\FeedbackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PanelController
 * @package AppBundle\Controller\Api\V1
 */
class PanelController extends AbstractController
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
    /**
     * @var User $user
     */
    $user = $this->getDoctrine()->getManager()
      ->getRepository(User::class)
      ->findOneBy(['login' => $request->get('user')]);
    if (!$user)
    {
      return new JsonResponse('user not found', 404, [], true);
    }
    $nbReviews = $this->feedbackRepository
      ->countUserReviews($user);

    return new JsonResponse(json_encode([
      'nbReviews' => $nbReviews
    ]), 200, [], true);
  }
}
