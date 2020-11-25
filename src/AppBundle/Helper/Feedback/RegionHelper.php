<?php

namespace AppBundle\Helper\Feedback;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Class RegionHelper
 * @package AppBundle\Helper\Feedback
 */
class RegionHelper
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
    $stmt = $conn->prepare('SELECT * FROM b_iblock_section WHERE IBLOCK_ID = 16 AND ACTIVE = "Y"');
    $stmt->execute();

    $result = $stmt->fetchAll();
    $nbImported = 0;
    foreach ($result as $key => $item) {
      $name = !empty($item['SEARCHABLE_CONTENT']) ? $item['SEARCHABLE_CONTENT'] : null;
      $code = !empty($item['CODE']) ? (trim(mb_substr($item['CODE'], 0, 3))) : null;

      $region = new \AppBundle\Entity\Geo\Region();
      $region->setName($name);
      $region->setCode($code);
      $this->entityManager->persist($region);
      $nbImported++;
    }
    $this->entityManager->flush();
    $io->success(sprintf('Fill Region: %s out of %s', $nbImported, count($result)));

    return $nbImported;
  }

  /**
   * @throws Throwable
   */
  public function check()
  {
    try {
      $sql = 'UPDATE s_company_branches scb 
              LEFT JOIN s_regions sr ON sr.id = scb.region_id
              SET scb.region_id = null
              WHERE scb.region_id IS NOT NULL AND sr.id IS NULL';
      $stmt = $this->entityManager->getConnection()->prepare($sql);
      $stmt->execute();
    } catch (Throwable $exception) {
      $this->logger->error(sprintf('Error update s_company_branches: . %s', $exception->getMessage()));

      throw $exception;
    }
  }
}