<?php

namespace AppBundle\Helper\Feedback;

use AppBundle\Entity\Geo\Region;
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
    $sql = 'SELECT 
                B_IS.ID, 
                B_IS.SEARCHABLE_CONTENT, 
                B_IS.CODE, 
                B_IS9.ID AS ID_9
            FROM b_iblock_section B_IS 
            LEFT JOIN b_iblock_section B_IS9 ON SUBSTRING(B_IS9.SEARCHABLE_CONTENT, 1, 3) = SUBSTRING(B_IS.SEARCHABLE_CONTENT, 1, 3) AND B_IS9.IBLOCK_ID = 9
            WHERE B_IS.IBLOCK_ID = 16';
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $nbImported = 0; $nbTotal = 0; $nbUpdated = 0;
    while ($item = $stmt->fetch()) {
      $bitrixId = !empty($item['ID']) ? $item['ID'] : null;
      $bitrixCityHospitalId = !empty($item['ID_9']) ? $item['ID_9'] : null;
      $name = !empty($item['SEARCHABLE_CONTENT']) ? $item['SEARCHABLE_CONTENT'] : null;
      $code = !empty($item['CODE']) ? (trim(mb_substr($item['CODE'], 0, 3))) : null;

      $region = $this->entityManager->getRepository(Region::class)->findOneBy(['bitrixId' => $item['ID']]);
      if (!$region)
      {
        $region = $this->entityManager->getRepository(Region::class)->findOneBy(['code' => $code]);
        if (!$region)
        {
          if (!empty($item['CODE']))
          {
            $region = $this->entityManager->getRepository(Region::class)->findOneBy(['code' => $item['CODE']]);
          }

          if (!$region)
          {
            $region = new \AppBundle\Entity\Geo\Region();
          }

          $nbImported++;
        }

        $region->setBitrixId($bitrixId);
      }
      else
      {
        $nbUpdated++;
      }

      $region->setBitrixCityHospitalId($bitrixCityHospitalId);
      $region->setName($name);
      $region->setCode($code);

      $this->entityManager->persist($region);

      $nbTotal++;
    }
    $this->entityManager->flush();
    $io->success(sprintf('Region import: %s found, %s added, %s updated', $nbTotal, $nbImported, $nbUpdated));

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
