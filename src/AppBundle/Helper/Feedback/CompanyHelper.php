<?php

namespace AppBundle\Helper\Feedback;

use AppBundle\Entity\Company\Company;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Class CompanyHelper
 * @package AppBundle\Helper\Feedback
 */
class CompanyHelper
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
    $sql = 'SELECT e.ID, 
                    e.NAME, 
                    e.IBLOCK_ID, 
                    e.ACTIVE, 
                    epKpp.VALUE as KPP,
                    epI.VALUE as IMAGE   
            FROM b_iblock_element e
            LEFT JOIN b_iblock_element_property epKpp ON epKpp.IBLOCK_ELEMENT_ID = e.ID AND epKpp.IBLOCK_PROPERTY_ID = 144    
            LEFT JOIN b_iblock_element_property epI ON epI.IBLOCK_ELEMENT_ID = e.ID AND epI.IBLOCK_PROPERTY_ID = 139                     
            WHERE e.IBLOCK_ID = 24';
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $result = $stmt->fetchAll();
    $nbImported = 0;
    foreach ($result as $item)
    {
      $name = !empty($item['NAME']) ? str_replace('"', '', $item['NAME']) : null;
      $kpp = !empty($item['KPP']) ? $item['KPP'] : null;
      $image = !empty($item['IMAGE']) ? $item['IMAGE'] : null;

      $company = new Company();
      $company->setName($name);
      $company->setKpp($kpp);
      $company->setFile($image);
      $this->entityManager->persist($company);
      $nbImported++;
    }
    $this->entityManager->flush();
    $io->success(sprintf('Fill Company: %s out of %s', $nbImported, count($result)));

    return $nbImported;
  }

  /**
   * @throws Throwable
   */
  public function check()
  {
    $this->checkBranch();
  }

  /**
   * @throws Throwable
   */
  private function checkBranch()
  {
    try
    {
      $sql = 'UPDATE s_company_branches scb 
              LEFT JOIN s_companies sc ON sc.id = scb.company_id
              SET scb.company_id = null
              WHERE scb.company_id IS NOT NULL AND sc.id IS NULL';
      $stmt = $this->entityManager->getConnection()->prepare($sql);
      $stmt->execute();
    } catch (Throwable $exception)
    {
      $this->logger->error(sprintf('Error update s_company_branches: . %s', $exception->getMessage()));

      throw $exception;
    }
  }
}