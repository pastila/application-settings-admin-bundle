<?php


namespace AppBundle\Controller;


use AppBundle\Exception\BitrixRequestException;
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
    $nbAppeals = null;
    $nbSent = null;

    $dataFromBitrix = new DataFromBitrix($request);
    try {
      $dataFromBitrix->getData('%s/ajax/get_obrashcheniya.php');
      $nbAppeals = $dataFromBitrix->getParam('nbAppeals');
      $nbSent = $dataFromBitrix->getParam('nbSent');
    } catch (BitrixRequestException $exception) {
      $this->logger->error(sprintf('Error get from bitrix panel: . %s', $exception->getMessage()));
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