<?php

namespace AppBundle\Controller\Api\V1;

use AppBundle\Entity\Obrashcheniya\ObrashcheniyaFile;
use AppBundle\Entity\User\User;
use AppBundle\Model\Obrashchenia\AppealDataParse;
use AppBundle\Service\Obrashcheniya\ObrashcheniaBranchMailer;
use AppBundle\Service\Obrashcheniya\ObrashcheniaUserMailer;
use AppBundle\Util\BitrixHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
   * AppealController constructor.
   * @param $apiToken
   */
  public function __construct(
    $apiToken,
    EntityManagerInterface $entityManager,
    LoggerInterface $logger,
    ObrashcheniaBranchMailer $mailerBranch,
    ObrashcheniaUserMailer $userMailer,
    AppealDataParse $appealDataParse,
    BitrixHelper $bitrixHelper
  )
  {
    $this->apiToken = $apiToken;
    $this->entityManager = $entityManager;
    $this->logger = $logger;
    $this->mailerBranch = $mailerBranch;
    $this->userMailer = $userMailer;
    $this->appealDataParse = $appealDataParse;
    $this->bitrixHelper = $bitrixHelper;
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
    $data = json_decode($request->get('data'), true);

    if (empty($data))
    {
      $this->logger->error('Empty body from Obrashcheniya Files in RabbitMq');
      return new Response(null, 400);
    }

    $author = $this->entityManager->getRepository(User::class)
      ->findOneBy(['login' => key_exists('user_login', $data) ? $data['user_login'] : null]);

    $model = new ObrashcheniyaFile();
    $model->setAuthor($author);
    $model->setType(key_exists('file_type', $data) ? $data['file_type'] : null);
    $model->setFile(key_exists('file_name', $data) ? $data['file_name'] : null);
    $model->setBitrixId(key_exists('obrashcheniya_id', $data) ? $data['obrashcheniya_id'] : null);
    $model->setImageNumber(key_exists('imageNumber', $data) ? $data['imageNumber'] : null);
    $this->entityManager->persist($model);
    $this->entityManager->flush();

    return new JsonResponse(null, 200, [], true);
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
    $this->logger->info(sprintf('Get data from bitrix by RabbitMq in appeal: %s', $data));

    try
    {
      $modelAppealData = $this->appealDataParse->parse($data);
    } catch (\Exception $e)
    {
      $this->logger->error(sprintf('Exception in parsing appeal: %s', $e));

      /**
       * Установить для обращения статус, что оно не было отправлено
       */
      $this->bitrixHelper->updatePropertyElementValue(11, $data['id'], 'SEND_REVIEW', 9, 9, null);

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

      /**
       * Установить для обращения статус, что оно не было отправлено
       */
      $this->bitrixHelper->updatePropertyElementValue(11, $modelAppealData->getBitrixId(), 'SEND_REVIEW', 9, 9, null);

      return new Response(null, 400);
    }

    $router = $this->get('router');

    $context = $router->getContext();
    $context->setHost($this->getParameter('router.request_context.host'));
    $context->setScheme($this->getParameter('router.request_context.scheme'));

    # Отправка сообщения пользователю, что обращение отправлено
    $this->userMailer->send($modelAppealData);

    return new JsonResponse(null, 200, [], true);
  }
}
