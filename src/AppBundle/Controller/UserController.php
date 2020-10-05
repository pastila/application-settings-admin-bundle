<?php


namespace AppBundle\Controller;


use AppBundle\Helper\DataFromBitrix;
use AppBundle\Repository\Company\FeedbackRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserController
 * @package AppBundle\Controller
 */
class UserController extends AbstractController
{
  /**
   * @var FeedbackRepository
   */
  private $feedbackRepository;

  /**
   * @var LoggerInterface
   */
  protected $logger;

  /**
   * UserController constructor.
   * @param FeedbackRepository $feedbackRepository
   */
  public function __construct(
    FeedbackRepository $feedbackRepository,
    LoggerInterface $logger
  )
  {
    $this->feedbackRepository = $feedbackRepository;
    $this->logger = $logger;
  }

  /**
   * @param Request $request
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function _userPanelAction(Request $request)
  {
    $dataFromBitrix = new DataFromBitrix($request, $this->logger);
    $dataFromBitrix->getData('%s/ajax/get_obrashcheniya.php');

    if (!empty($dataFromBitrix->getInfo()['http_code']) && $dataFromBitrix->getInfo()['http_code'] === 200)
    {
      $res = json_decode($dataFromBitrix->getRes(), true);

      $nbAppeals = isset($res['nbAppeals']) ? $res['nbAppeals'] : null;
      $nbSent = isset($res['nbSent']) ? $res['nbSent'] : null;
    }
    else
    {
      $nbAppeals = null;
      $nbSent = null;
    }

    $nbReviews = $this->feedbackRepository
      ->countUserReviews($this->get('security.token_storage')->getToken()->getUser());

    return $this->render('User/_panel.html.twig', [
      'nbReviews' => $nbReviews,
      'nbAppeals' => $nbAppeals,
      'nbSent' => $nbSent,
    ]);
  }
}