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
                    ANY_VALUE(epKpp.VALUE) as KPP,
                    ANY_VALUE(epI.VALUE) as IMAGE,                    
                    ANY_VALUE(epE1.VALUE) as EMAIL1,                    
                    ANY_VALUE(epE2.VALUE) as EMAIL2,                    
                    ANY_VALUE(epE2.VALUE) as EMAIL3,
                    ANY_VALUE(ieB.NAME) as NAME_BRANCH
            FROM b_iblock_element e
            LEFT JOIN b_iblock_element_property epKpp ON epKpp.IBLOCK_ELEMENT_ID = e.ID AND epKpp.IBLOCK_PROPERTY_ID = 144    
            LEFT JOIN b_iblock_element_property epI ON epI.IBLOCK_ELEMENT_ID = e.ID AND epI.IBLOCK_PROPERTY_ID = 139                
            LEFT JOIN b_iblock_element_property epE1 ON epE1.IBLOCK_ELEMENT_ID = e.ID AND epE1.IBLOCK_PROPERTY_ID = 135      
            LEFT JOIN b_iblock_element_property epE2 ON epE2.IBLOCK_ELEMENT_ID = e.ID AND epE2.IBLOCK_PROPERTY_ID = 136      
            LEFT JOIN b_iblock_element_property epE3 ON epE3.IBLOCK_ELEMENT_ID = e.ID AND epE3.IBLOCK_PROPERTY_ID = 137   
            LEFT JOIN b_iblock_element_property epK ON epK.VALUE = epKpp.VALUE AND epK.IBLOCK_PROPERTY_ID = 112
            LEFT JOIN b_iblock_element ieB ON ieB.ID = epK.IBLOCK_ELEMENT_ID
            WHERE e.IBLOCK_ID = 24 AND e.ACTIVE = "Y"
            GROUP BY e.ID';
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $result = $stmt->fetchAll();
    $nbImported = 0;
    foreach ($result as $item) {
      $name = !empty($item['NAME_BRANCH']) ? $item['NAME_BRANCH'] : $item['NAME'];
      $name = str_replace('"', '', $name);
      $name = trim($name);
      $kpp = !empty($item['KPP']) ? $item['KPP'] : null;
      $image = !empty($item['IMAGE']) ? $item['IMAGE'] : null;
      $email1 = !empty($item['EMAIL1']) ? $item['EMAIL1'] : null;
      $email2 = !empty($item['EMAIL2']) ? $item['EMAIL2'] : null;
      $email3 = !empty($item['EMAIL3']) ? $item['EMAIL3'] : null;

      $company = new Company();
      $company->setName($name);
      $company->setKpp($kpp);
      $company->setFile($image);
      $company->setEmailFirst($email1);
      $company->setEmailSecond($email2);
      $company->setEmailThird($email3);
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
    try {
      $sql = 'UPDATE s_company_branches scb 
              LEFT JOIN s_companies sc ON sc.id = scb.company_id
              SET scb.company_id = null
              WHERE scb.company_id IS NOT NULL AND sc.id IS NULL';
      $stmt = $this->entityManager->getConnection()->prepare($sql);
      $stmt->execute();
    } catch (Throwable $exception) {
      $this->logger->error(sprintf('Error update s_company_branches: . %s', $exception->getMessage()));

      throw $exception;
    }
  }
}