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

  /**
   * @Route("/api/v1/appeal-email", name="api_appeal_email", methods={"GET"}, requirements={ "api_token": "\d+" }))
   */
  public function getAppealEmailAction(Request $request)
  {
    if ($this->apiToken !== $request->get('api_token'))
    {
      return new Response(null, 403);
    }
    $data = json_decode($request->get('data'), true);
    $this->logger->info(sprintf('Get data from bitrix by curl in appeal: %s', $data));

    try
    {
      $modelAppealData = $this->appealDataParse->parse($data);
    } catch (\Exception $e)
    {
      $this->logger->error(sprintf('Exception in parsing appeal: %s', $e));
      return new Response(null, 400);
    }

    try
    {
      foreach ($modelAppealData->getEmailsTo() as $email)
      {
        $this->mailerBranch->send($modelAppealData, $email);
      }
    } catch (\Swift_TransportException $e)
    {
      $this->logger->error(sprintf('Transport Exception in sending appeal to branch company: %s', $e));
      return new Response(null, 400);
    } catch (\Exception $e2)
    {
      $this->logger->error(sprintf('Exception in sending appeal to branch company: %s', $e2));
      return new Response(null, 400);
    }

    $router = $this->get('router');
    $context = $router->getContext();
    $context->setHost($this->getParameter('router.request_context.host'));
    $context->setScheme($this->getParameter('router.request_context.scheme'));

    # Отправка сообщения пользователю, что обращение отправлено
    $this->userMailer->send($modelAppealData);

    return new Response(null, 200);
  }
}
