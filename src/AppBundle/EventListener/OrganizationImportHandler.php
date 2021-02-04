<?php

namespace AppBundle\EventListener;

use Accurateweb\TaskSchedulerBundle\Event\BackgroundJobEvent;
use Accurateweb\TaskSchedulerBundle\Service\BackgroundJob\BackgroundJobManager;
use Accurateweb\TaskSchedulerBundle\Service\BackgroundJob\SimpleBackgroundJob;
use AppBundle\Service\Organization\OrganizationImportMailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Psr\Log\LoggerInterface;

/**
 * Class OrganizationImportHandler
 * @package AppBundle\EventListener
 */
class OrganizationImportHandler implements EventSubscriberInterface
{
  private $jobManager;
  private $organizationImportMailer;
  private $logger;

  public function __construct (
    BackgroundJobManager $jobManager,
    OrganizationImportMailer $organizationImportMailer,
    LoggerInterface $logger
  )
  {
    $this->jobManager = $jobManager;
    $this->organizationImportMailer = $organizationImportMailer;
    $this->logger = $logger;
  }

  public function onEnd(BackgroundJobEvent $event)
  {
    $backgroundJobService = $event->getBackgroundJobService();
    if ($backgroundJobService->getClsid() !== '88436faa-55aa-4f76-a491-47b750b2b939')
    {
      return;
    }

    try
    {
      $this->organizationImportMailer->send($event->getBackgroundJob());
    }
    catch (\Exception $e)
    {
      $this->logger->error(sprintf('Failed to send organization import result to operator. %s', $e->getMessage()));
    }
  }

  public static function getSubscribedEvents()
  {
    return [
      'job.end' => ['onEnd'],
    ];
  }
}