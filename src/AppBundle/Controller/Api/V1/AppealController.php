<?php

namespace AppBundle\Controller\Api\V1;

use AppBundle\Entity\Obrashcheniya\ObrashcheniyaFile;
use AppBundle\Entity\User\User;
use AppBundle\Model\Obrashchenia\AppealDataParse;
use AppBundle\Service\Obrashcheniya\AppealToUserConnector;
use AppBundle\Service\Obrashcheniya\ObrashcheniaBranchMailer;
use AppBundle\Service\Obrashcheniya\ObrashcheniaUserMailer;
use AppBundle\Util\BitrixHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;

/**
 * Class AppealController
 * @package AppBundle\Controller\Api\V1
 */
class AppealController extends Controller
{
  /**
   * @var EntityManagerInterface
   */
  private $entityManager;

  /**
   * @var LoggerInterface
   */
  private $logger;

  /**
   * @var
   */
  private $apiToken;

  /**
   * @var ObrashcheniaBranchMailer
   */
  private $mailerBranch;

  /**
   * @var ObrashcheniaUserMailer
   */
  private $userMailer;

  /**
   * @var AppealDataParse
   */
  private $appealDataParse;

  /**
   * @var BitrixHelper
   */
  private $bitrixHelper;

  /**
   * @var AppealToUserConnector
   */
  private $connector;

  /**
   * AppealController constructor.
   * @param $apiToken
   * @param EntityManagerInterface $entityManager
   * @param LoggerInterface $logger
   * @param ObrashcheniaBranchMailer $mailerBranch
   * @param ObrashcheniaUserMailer $userMailer
   * @param AppealDataParse $appealDataParse
   * @param BitrixHelper $bitrixHelper
   * @param AppealToUserConnector $connector
   */
  public function __construct(
    $apiToken,
    EntityManagerInterface $entityManager,
    LoggerInterface $logger,
    ObrashcheniaBranchMailer $mailerBranch,
    ObrashcheniaUserMailer $userMailer,
    AppealDataParse $appealDataParse,
    BitrixHelper $bitrixHelper,
    AppealToUserConnector $connector
  )
  {
    $this->apiToken = $apiToken;
    $this->entityManager = $entityManager;
    $this->logger = $logger;
    $this->mailerBranch = $mailerBranch;
    $this->userMailer = $userMailer;
    $this->appealDataParse = $appealDataParse;
    $this->bitrixHelper = $bitrixHelper;
    $this->connector = $connector;
  }

  /**
   * @Route("/api/v1/appeal-files", name="api_appeal_files", methods={"GET"}, requirements={ "api_token": "\d+" }))
   */
  public function getAppealFilesAction(Request $request)
  {
    if ($this->apiToken !== $request->get('api_token'))
    {
      return new Response(null, 403);
    }

    try
    {
      $this->connector->saveAppealToUserConnection(json_decode($request->get('data'), true));
    }
    catch (\InvalidArgumentException $e)
    {
      $this->logger->error($e);

      return new Response(null, 400);
    }

    return new Response(null, 200);
  }
}
