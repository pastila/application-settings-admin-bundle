<?php

namespace AppBundle\Helper\Feedback;

use AppBundle\Entity\Company\InsuranceCompanyBranch;
use AppBundle\Entity\Geo\Region;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Class CompanyBranchHelper
 * @package AppBundle\Helper\Feedback
 */
class CompanyBranchHelper
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
   * CompanyBranchHelper constructor.
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
                e.CODE, 
                e.ACTIVE, 
                epK.VALUE as KPP,
                sC.ID as COMPANY_ID,
                epR.SEARCHABLE_CONTENT as REGION_NAME,
                sR.id as REGION_ID,
                epVS.VALUE as AMOUNT_STARS,
                epVAS.VALUE as ALL_AMOUNT_STAR,
                epIMG.VALUE as IMAGE_ID,                                 
                epE1.VALUE as EMAIL1,                    
                epE2.VALUE as EMAIL2,                    
                epE3.VALUE as EMAIL3
            FROM b_iblock_element e
            LEFT JOIN b_iblock_element_property epK ON epK.IBLOCK_ELEMENT_ID = e.ID AND epK.IBLOCK_PROPERTY_ID = 112     
            LEFT JOIN s_companies sC ON sC.kpp = epK.VALUE  
            LEFT JOIN b_iblock_section epR ON e.IBLOCK_SECTION_ID = epR.ID
            LEFT JOIN s_regions sR ON (sR.name LIKE epR.SEARCHABLE_CONTENT)            
            LEFT JOIN b_iblock_element_property epVS ON epVS.IBLOCK_ELEMENT_ID = e.ID AND epVS.IBLOCK_PROPERTY_ID = 146                
            LEFT JOIN b_iblock_element_property epVAS ON epVAS.IBLOCK_ELEMENT_ID = e.ID AND epVAS.IBLOCK_PROPERTY_ID = 131           
            LEFT JOIN b_iblock_element_property epIMG ON epIMG.IBLOCK_ELEMENT_ID = e.ID AND epIMG.IBLOCK_PROPERTY_ID = 85                      
            LEFT JOIN b_iblock_element_property epE1 ON epE1.IBLOCK_ELEMENT_ID = e.ID AND epE1.IBLOCK_PROPERTY_ID = 81      
            LEFT JOIN b_iblock_element_property epE2 ON epE2.IBLOCK_ELEMENT_ID = e.ID AND epE2.IBLOCK_PROPERTY_ID = 82      
            LEFT JOIN b_iblock_element_property epE3 ON epE3.IBLOCK_ELEMENT_ID = e.ID AND epE3.IBLOCK_PROPERTY_ID = 83       
            WHERE e.IBLOCK_ID = 16';
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $nbImported = 0; $nbTotal = 0; $nbUpdated = 0;
    while ($item = $stmt->fetch())
    {
      $nbTotal++;

      $name = !empty($item['NAME']) ? str_replace('"', '', $item['NAME']) : null;
      $kpp = !empty($item['KPP']) ? $item['KPP'] : null;
      $company_id = !empty($item['COMPANY_ID']) ? $item['COMPANY_ID'] : null;
      $region_id = !empty($item['REGION_ID']) ? $item['REGION_ID'] : null;
      $amountStar = !empty($item['AMOUNT_STARS']) ? (float)$item['AMOUNT_STARS'] : 0;

      $image_id = !empty($item['IMAGE_ID']) ? $item['IMAGE_ID'] : null;
      $email1 = !empty($item['EMAIL1']) ? $item['EMAIL1'] : null;
      $email2 = !empty($item['EMAIL2']) ? $item['EMAIL2'] : null;
      $email3 = !empty($item['EMAIL3']) ? $item['EMAIL3'] : null;
      $status = !empty($item['ACTIVE']) ?
        ($item['ACTIVE'] === 'Y' ? true : false) :
        false;

      $company = !empty($company_id) ? $this->entityManager->getRepository("InsuranceCompany")
        ->createQueryBuilder('c')
        ->andWhere('c.id = :id')
        ->setParameter('id', $company_id)
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult() : null;

      if (empty($company))
      {
        $io->warning(sprintf('Branch %s for region %s not imported: no company found', $name, $region_id));
        continue;
      }

      $region = $this->entityManager->getRepository(Region::class)->find($region_id);
      if (!$region)
      {
        $io->warning(sprintf('Branch %s for region %s not imported: no region found', $name, $region_id));
        continue;
      }


      if ($company)
      {
        $branch = $this->entityManager->getRepository(InsuranceCompanyBranch::class)->findOneBy(['bitrixId' => $item['ID']]);

        if (!$branch)
        {
          $branch = $this->entityManager->getRepository(InsuranceCompanyBranch::class)->findOneBy([
            'region' => $region,
            'company' => $company
          ]);

          if (!$branch)
          {
            $branch = new InsuranceCompanyBranch();
            $branch->setValuation($amountStar);

            $nbImported++;
          }

          $branch->setBitrixId($item['ID']);
        }
        else
        {
          $nbUpdated++;
        }

        $branch->setName($name);
        $branch->setKpp($kpp);
        $branch->setCode($kpp);
        $branch->setCompany($company);
        $branch->setRegion($region);
        $branch->setLogoIdFromBitrix($image_id);
        $branch->setEmailFirst($email1);
        $branch->setEmailSecond($email2);
        $branch->setEmailThird($email3);
        $branch->setPublished($status);

        $this->entityManager->persist($branch);
      }
    }

    $this->entityManager->flush();

    $io->success(sprintf('Company branch import: %s found, %s added, %s updated', $nbTotal, $nbImported, $nbUpdated));

    return $nbImported;
  }

  /**
   * @throws Throwable
   */
  public function check()
  {
    $this->checkFeedback();
    $this->checkUser();
  }

  /**
   * @throws Throwable
   */
  private function checkFeedback()
  {
    try
    {
      $sql = 'UPDATE s_company_feedbacks scf 
              LEFT JOIN s_company_branches scb ON scb.id = scf.branch_id
              SET scf.branch_id = null
              WHERE scf.branch_id IS NOT NULL AND scb.id IS NULL';
      $stmt = $this->entityManager->getConnection()->prepare($sql);
      $stmt->execute();
    } catch (Throwable $exception)
    {
      $this->logger->error(sprintf('Error update s_company_feedbacks: . %s', $exception->getMessage()));

      throw $exception;
    }
  }


  /**
   * @throws Throwable
   */
  private function checkUser()
  {
    try
    {
      $sql = 'UPDATE s_users su 
              LEFT JOIN s_company_branches scb ON scb.id = su.branch_id
              SET su.branch_id = null
              WHERE su.branch_id IS NOT NULL AND scb.id IS NULL';
      $stmt = $this->entityManager->getConnection()->prepare($sql);
      $stmt->execute();
    } catch (Throwable $exception)
    {
      $this->logger->error(sprintf('Error update s_users: . %s', $exception->getMessage()));

      throw $exception;
    }
  }
}
