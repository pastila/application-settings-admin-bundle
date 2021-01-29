<?php

namespace AppBundle\Command;

use AppBundle\Entity\Company\Citation;
use AppBundle\Entity\Company\Comment;
use AppBundle\Entity\Company\InsuranceCompany;
use AppBundle\Entity\Company\InsuranceCompanyBranch;
use AppBundle\Entity\Company\Feedback;
use AppBundle\Entity\Company\FeedbackModerationStatus;
use AppBundle\Entity\Geo\Region;
use AppBundle\Entity\User\User;
use AppBundle\Helper\Feedback\CommonHelper;
use AppBundle\Helper\Feedback\CompanyBranchHelper;
use AppBundle\Helper\Feedback\RegionHelper;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class BitrixImportCommand
 * @package AppBundle\Command
 */
class BitrixImportCommand extends ContainerAwareCommand
{
  /**
   *
   */
  protected function configure()
  {
    $this
      ->setName('bitrix:import')
      ->setDescription('Init tables symfony use by bitrix');
  }

  /**
   * @param InputInterface $input
   * @param OutputInterface $output
   * @return int|void
   * @throws \Exception
   */
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $io = new SymfonyStyle($input, $output);
    $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
    $doctrine = $this->getContainer()->get('doctrine');
    $this->clearTables($entityManager);

    $common = new CommonHelper();
    $common->clearTable($entityManager, [Region::class]);
    $regionHelper = $this->getContainer()->get('AppBundle\Helper\Feedback\RegionHelper');
    $regionHelper->load($io);

    $common = new CommonHelper();
    $common->clearTable($entityManager, [InsuranceCompany::class]);
    $companyHelper = $this->getContainer()->get('AppBundle\Helper\Feedback\CompanyHelper');
    $companyHelper->load($io);

    $common = new CommonHelper();
    $common->clearTable($entityManager, [InsuranceCompanyBranch::class]);
    $companyBranchHelper = $this->getContainer()->get('AppBundle\Helper\Feedback\CompanyBranchHelper');
    $companyBranchHelper->load($io);

    $this->fillUser($entityManager, $input, $output);
    $this->fillFeedback($entityManager, $doctrine, $input, $output);
    $this->fillComment($entityManager, $doctrine, $input, $output);
    $this->fillCitation($entityManager, $doctrine, $input, $output);
  }

  /**
   * @param $entityManager
   */
  public function clearTables($entityManager)
  {
    $tables = [
      User::class,
      Region::class,
      InsuranceCompany::class,
      InsuranceCompanyBranch::class,
      Feedback::class,
      Comment::class,
      Citation::class
    ];
    $common = new CommonHelper();
    $common->clearTable($entityManager, $tables);
  }

  /**
   * @param $entityManager
   * @param $tables
   */
  public function clearTable($entityManager, $tables)
  {
    $connection = $entityManager->getConnection();
    foreach ($tables as $table)
    {
      $metaData = $entityManager->getClassMetadata($table);
      $connection->query('SET FOREIGN_KEY_CHECKS=0');
      $query = $connection->getDatabasePlatform()->getTruncateTableSQL($metaData->getTableName());
      $connection->executeUpdate($query);
      $connection->query('SET FOREIGN_KEY_CHECKS=1');
    }
  }

  /**
   * @param $entityManager
   */
  private function fillUser($entityManager, InputInterface $input, OutputInterface $output)
  {
    $conn = $entityManager->getConnection();
    $sql = 'SELECT 
                    u.LOGIN,                     
                    u.EMAIL,
                    u.LAST_NAME,
                    u.NAME,
                    u.SECOND_NAME,
                    uu.UF_REPRESENTATIVE as REPRESENTATIVE,   
                    sr.SEARCHABLE_CONTENT as REGION_NAME, 
                    srg.id as REGION_ID,                                     
                    ec.SEARCHABLE_CONTENT as COMPANY_NAME,                      
                    cp_kpp.VALUE as KPP,  
                    scb.id as BRANCH_ID                   
            FROM b_user u
            LEFT JOIN b_uts_user uu ON uu.VALUE_ID = u.ID
            LEFT JOIN b_iblock_section sr ON sr.ID = uu.UF_REGION AND uu.UF_REPRESENTATIVE = 1
            LEFT JOIN s_regions srg ON (srg.name LIKE sr.SEARCHABLE_CONTENT)        
            LEFT JOIN b_iblock_element ec ON ec.ID = uu.UF_INSURANCE_COMPANY AND uu.UF_REPRESENTATIVE = 1
            LEFT JOIN b_iblock_element_property cp_kpp ON cp_kpp.IBLOCK_ELEMENT_ID = ec.ID AND cp_kpp.IBLOCK_PROPERTY_ID = 112
            LEFT JOIN s_company_branches scb ON scb.kpp = cp_kpp.VALUE AND scb.region_id = srg.id';
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $result = $stmt->fetchAll();
    foreach ($result as $item)
    {
      $login = !empty($item['LOGIN']) ? $item['LOGIN'] : null;
      $email = !empty($item['EMAIL']) ? $item['EMAIL'] : null;
      $lastName = !empty($item['LAST_NAME']) ? $item['LAST_NAME'] : null;
      $firstName = !empty($item['NAME']) ? $item['NAME'] : null;
      $middleName = !empty($item['SECOND_NAME']) ? $item['SECOND_NAME'] : null;
      $representative = !empty($item['REPRESENTATIVE']) ? $item['REPRESENTATIVE'] : null;
      $branchId = !empty($item['BRANCH_ID']) ? $item['BRANCH_ID'] : null;

      $sql = 'INSERT INTO s_users(login, email, last_name, first_name, middle_name, representative, branch_id) 
              VALUES(:login, :email, :lastName, :firstName, :middleName, :representative, :branchId)';
      $stmt = $conn->prepare($sql);
      $stmt->bindValue('login', $login);
      $stmt->bindValue('email', $email);
      $stmt->bindValue('lastName', $lastName);
      $stmt->bindValue('firstName', $firstName);
      $stmt->bindValue('middleName', $middleName);
      $stmt->bindValue('representative', $representative);
      $stmt->bindValue('branchId', $branchId);
      $stmt->execute();
    }

    $io = new SymfonyStyle($input, $output);
    $io->success('Fill User:' . count($result));
  }

  /**
   * @param $entityManager
   * @param $doctrine
   * @param InputInterface $input
   * @param OutputInterface $output
   * @throws \Exception
   */
  private function fillFeedback($entityManager, $doctrine, InputInterface $input, OutputInterface $output)
  {
    $conn = $entityManager->getConnection();
    $sql = 'SELECT  e.ID, 
                    e.SEARCHABLE_CONTENT, 
                    e.DATE_CREATE, 
                    sR.NAME as REGION_NAME, 
                    sRg.id as REGION_ID, 
                    epT.VALUE as TEXT, 
                    epV.VALUE as VALUATION, 
                    epK.VALUE as KPP, 
                    epU.VALUE as USER_NAME,                    
                    epRL.VALUE as REVIEW_LETTER,              
                    epST.VALUE as VERIFIED,           
                    epRT.VALUE as REJECTED
            FROM b_iblock_element e 
            LEFT JOIN b_iblock_element_property epR ON epR.IBLOCK_ELEMENT_ID = e.ID AND epR.IBLOCK_PROPERTY_ID = 60    
            LEFT JOIN b_iblock_section sR ON sR.ID = epR.VALUE  
            LEFT JOIN s_regions sRg ON (sRg.name LIKE sR.SEARCHABLE_CONTENT)                        
            LEFT JOIN b_iblock_element_property epT ON epT.IBLOCK_ELEMENT_ID = e.ID AND epT.IBLOCK_PROPERTY_ID = 61    
            LEFT JOIN b_iblock_element_property epV ON epV.IBLOCK_ELEMENT_ID = e.ID AND epV.IBLOCK_PROPERTY_ID = 62     
            LEFT JOIN b_iblock_element_property epST ON epST.IBLOCK_ELEMENT_ID = e.ID AND epST.IBLOCK_PROPERTY_ID = 104  
            LEFT JOIN b_iblock_element_property epRT ON epRT.IBLOCK_ELEMENT_ID = e.ID AND epRT.IBLOCK_PROPERTY_ID = 105            
            LEFT JOIN b_iblock_element_property epRL ON epRL.IBLOCK_ELEMENT_ID = e.ID AND epRL.IBLOCK_PROPERTY_ID = 114    
            LEFT JOIN b_iblock_element_property epK ON epK.IBLOCK_ELEMENT_ID = e.ID AND epK.IBLOCK_PROPERTY_ID = 130   
            LEFT JOIN b_iblock_element_property epU ON epU.IBLOCK_ELEMENT_ID = e.ID AND epU.IBLOCK_PROPERTY_ID = 150    
            WHERE e.IBLOCK_ID = 13';
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $result = $stmt->fetchAll();
    foreach ($result as $item)
    {
      $bitrixId = !empty($item['ID']) ? $item['ID'] : null;
      $title = !empty($item['SEARCHABLE_CONTENT']) ? trim($item['SEARCHABLE_CONTENT']) : null;
      $text = !empty($item['TEXT']) ? trim($item['TEXT']) : null;
//      $regionName  = !empty($item['REGION_NAME']) ? ($item['REGION_NAME']) : null;
      $valuation = !empty($item['VALUATION']) ? ($item['VALUATION']) : null;
//      $kpp = !empty($item['KPP']) ? ($item['KPP']) : null;
//      $userName = !empty($item['USER_NAME']) ? ($item['USER_NAME']) : null;
      $created = !empty($item['DATE_CREATE']) ? $item['DATE_CREATE'] : null;
      $date = !empty($created) ? new \DateTime($created) : null;
      $reviewLetter = !empty($item['REVIEW_LETTER']) ? $item['REVIEW_LETTER'] : 0;
      $status = !empty($item['VERIFIED']) ?
        FeedbackModerationStatus::MODERATION_ACCEPTED : (!empty($item['REJECTED']) ? FeedbackModerationStatus::MODERATION_REJECTED : FeedbackModerationStatus::MODERATION_NONE);

      $region = !empty($item['REGION_NAME']) ? $doctrine->getRepository("AppBundle:Geo\Region")
        ->createQueryBuilder('r')
        ->andWhere('r.name LIKE :name')
        ->setParameter('name', '%' . $item['REGION_NAME'] . '%')
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult() : null;

      $fio = explode(" ", $item['USER_NAME']);
      $firstName = !empty($fio[1]) ? $fio[1] : '';
      $lastName = !empty($fio[0]) ? $fio[0] : '';
      $middleName = !empty($fio[2]) ? $fio[2] : '';
      $user = !empty($item['USER_NAME']) ? $doctrine->getRepository("AppBundle:User\User")
        ->createQueryBuilder('u')
        ->andWhere('u.firstName LIKE :firstName')
        ->andWhere('u.lastName LIKE :lastName')
        ->andWhere('u.middleName LIKE :middleName')
        ->setParameter('firstName', '%' . $firstName . '%')
        ->setParameter('lastName', '%' . $lastName . '%')
        ->setParameter('middleName', '%' . $middleName . '%')
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult() : null;
      if (empty($user))
      {
        $user = new User();
        $user->setLastName($lastName);
        $user->setFirstName($firstName);
        $user->setMiddleName($middleName);
        $entityManager->persist($user);
      }

      $branch = !empty($item['KPP']) ? $doctrine->getRepository("AppBundle:Company\InsuranceCompanyBranch")
        ->createQueryBuilder('cb')
        ->andWhere('cb.kpp = :kpp')
        ->andWhere('cb.region = :region_id')
        ->setParameter('kpp', $item['KPP'])
        ->setParameter('region_id', $region->getId())
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult() : null;

      $feedback = new Feedback();
      $feedback->setBitrixId($bitrixId);
      $feedback->setTitle($title);
      $feedback->setText($text);
      $feedback->setAuthor($user);
      $feedback->setBranch($branch);
      $feedback->setValuation($valuation);
      $feedback->setReviewLetter($reviewLetter);
      $feedback->setModerationStatus($status);
      $feedback->setCreatedAt($date);
      $feedback->setUpdatedAt($date);
      $entityManager->persist($feedback);
    }
    $entityManager->flush();

    $io = new SymfonyStyle($input, $output);
    $io->success('Fill Feedback:' . count($result));
  }

  /**
   * @param $entityManager
   * @param $doctrine
   */
  private function fillComment($entityManager, $doctrine, InputInterface $input, OutputInterface $output)
  {
    $conn = $entityManager->getConnection();
    $sql = 'SELECT e.ID, 
                e.DATE_CREATE, 
                epK.VALUE as COMMENTS
            FROM b_iblock_element e
            LEFT JOIN b_iblock_element_property epK ON epK.IBLOCK_ELEMENT_ID = e.ID AND epK.IBLOCK_PROPERTY_ID = 69         
            WHERE e.IBLOCK_ID = 14';
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $result = $stmt->fetchAll();
    $sql = '';
    foreach ($result as $item)
    {
      $text = !empty($item['COMMENTS']) ? $item['COMMENTS'] : null;
      $created = !empty($item['DATE_CREATE']) ? $item['DATE_CREATE'] : null;
      $date = !empty($created) ? date("Y-m-d H:i:s", strtotime($created)) : date("Y-m-d H:i:s");
      $status = FeedbackModerationStatus::MODERATION_NONE;

      $sql .= "INSERT INTO s_company_feedback_comments(text, created_at, updated_at, moderation_status) VALUES('$text', '$date', '$date', $status); ";
    }
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $io = new SymfonyStyle($input, $output);
    $io->success('Fill Comment:' . count($result));
  }

  /**
   * @param $entityManager
   * @param $doctrine
   */
  private function fillCitation($entityManager, $doctrine, InputInterface $input, OutputInterface $output)
  {
    // todo empty in base
  }
}
