<?php

namespace AppBundle\Helper\Organization;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Class OrganizationHelper
 * @package AppBundle\Helper\Organization
 */
class OrganizationHelper
{
  /**
   * @var EntityManagerInterface
   */
  private $entityManager;

  /**
   * @var LoggerInterface
   */
  protected $logger;

  /**
   * Region constructor.
   * @param EntityManagerInterface $entityManager
   * @param LoggerInterface $logger
   */
  public function __construct(
    EntityManagerInterface $entityManager,
    LoggerInterface $logger
  )
  {
    $this->entityManager = $entityManager;
    $this->logger = $logger;
  }

  /**
   * @param $io
   * @return int
   * @throws \Doctrine\DBAL\DBALException
   */
  public function load($io)
  {
    $conn = $this->entityManager->getConnection();
    $sql = '';
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $nbImported = 0; $nbTotal = 0; $nbUpdated = 0;
    while ($item = $stmt->fetch())
    {
      $nbTotal++;

    }
    $io->success(sprintf('Organization import: %s found, %s added, %s updated', $nbTotal, $nbImported, $nbUpdated));

    return $nbImported;
  }
}
