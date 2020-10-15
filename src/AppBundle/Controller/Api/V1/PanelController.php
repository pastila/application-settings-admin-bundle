<?php

namespace AppBundle\Controller\Api\V1;

use AppBundle\Entity\User\User;
use AppBundle\Repository\Company\FeedbackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
   * @var
   */
  private $apiToken;

  /**
   * PanelController constructor.
   * @param FeedbackRepository $feedbackRepository
   * @param $apiToken
   */
  public function __construct(
    FeedbackRepository $feedbackRepository,
    $apiToken
  )
  {
    $this->feedbackRepository = $feedbackRepository;
    $this->apiToken = $apiToken;
  }

  /**
   * @Route("/api/v1/panel", name="api_panel", methods={"GET"}, requirements={ "user": "\d+", "api_token": "\d+" }))
   */
  public function getPanelAction(Request $request)
  {
    if ($this->apiToken !== $request->get('api_token'))
    {
      return new Response(null, 403);
    }

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
