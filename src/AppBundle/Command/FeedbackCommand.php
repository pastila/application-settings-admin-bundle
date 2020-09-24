<?php

namespace AppBundle\Command;

use AppBundle\Entity\Company\Citation;
use AppBundle\Entity\Company\Comment;
use AppBundle\Entity\Company\Company;
use AppBundle\Entity\Company\CompanyBranch;
use AppBundle\Entity\Company\Feedback;
use AppBundle\Entity\Company\FeedbackModerationStatus;
use AppBundle\Entity\Geo\Region;
use AppBundle\Entity\User\User;
use DateTime;
use Doctrine\ORM\EntityManager as EntityManagerAlias;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpFoundation\Response;

class FeedbackCommand extends ContainerAwareCommand
{
  /**
   *
   */
  protected function configure()
  {
    $this
      ->setName('feedback:iblock')
      ->setDescription('Set feedback');
  }

  /**
   * @param InputInterface $input
   * @param OutputInterface $output
   * @return int|void
   * @throws \Exception
   */
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
    $doctrine = $this->getContainer()->get('doctrine');

    $this->clearTable($entityManager);
    $this->fillRegion($entityManager, $input, $output);
    $this->fillCompany($entityManager, $input, $output);
    $this->fillCompanyBranch($entityManager, $doctrine, $input, $output);
    $this->fillUser($entityManager, $input, $output);
//    $this->fillFeedback($entityManager, $doctrine, $input, $output);
//    $this->fillComment($entityManager, $doctrine, $input, $output);
//    $this->fillCitation($entityManager, $doctrine, $input, $output);
  }

  /**
   * @param $entityManager
   */
  public function clearTable($entityManager)
  {
    $connection = $entityManager->getConnection();
    $tables = [
      User::class,
      Region::class,
      Company::class,
      CompanyBranch::class,
      Feedback::class,
      Comment::class,
      Citation::class
    ];

    foreach ($tables as $table) {
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
  private function fillRegion($entityManager, InputInterface $input, OutputInterface $output)
  {
    $conn = $entityManager->getConnection();
    $stmt = $conn->prepare('SELECT * FROM b_iblock_section WHERE IBLOCK_ID = 16 AND ACTIVE = "Y"');
    $stmt->execute();

    $result = $stmt->fetchAll();
    foreach ($result as $key => $item) {
      $name = !empty($item['SEARCHABLE_CONTENT']) ? $item['SEARCHABLE_CONTENT'] : null;
      $code = !empty($item['CODE']) ? $item['CODE'] : null;

      $region = new Region();
      $region->setName($name);
      $region->setCode($code);
      $entityManager->persist($region);
    }
    $entityManager->flush();

    $io = new SymfonyStyle($input, $output);
    $io->success('Fill Region:' . count($result));
  }

  /**
   * @param $entityManager
   */
  private function fillUser($entityManager, InputInterface $input, OutputInterface $output)
  {
    $conn = $entityManager->getConnection();
    $sql = 'SELECT 
                    u.LOGIN, 
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
    foreach ($result as $item) {
      $login = !empty($item['LOGIN']) ? $item['LOGIN'] : null;
      $lastName = !empty($item['LAST_NAME']) ? $item['LAST_NAME'] : null;
      $firstName = !empty($item['NAME']) ? $item['NAME'] : null;
      $middleName = !empty($item['SECOND_NAME']) ? $item['SECOND_NAME'] : null;
      $representative = !empty($item['REPRESENTATIVE']) ? $item['REPRESENTATIVE'] : null;
      $branchId = !empty($item['BRANCH_ID']) ? $item['BRANCH_ID'] : null;

      $sql = 'INSERT INTO s_users(login, last_name, first_name, middle_name, representative, branch_id) 
              VALUES(:login, :lastName, :firstName, :middleName, :representative, :branchId)';
      $stmt = $conn->prepare($sql);
      $stmt->bindValue('login', $login);
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
   */
  private function fillCompany($entityManager, InputInterface $input, OutputInterface $output)
  {
    $conn = $entityManager->getConnection();
    $sql = 'SELECT e.ID, 
                    e.NAME, 
                    e.IBLOCK_ID, 
                    e.ACTIVE, 
                    epKpp.VALUE as KPP,
                    epI.VALUE as IMAGE
            FROM b_iblock_element e
            LEFT JOIN b_iblock_element_property epKpp ON epKpp.IBLOCK_ELEMENT_ID = e.ID AND epKpp.IBLOCK_PROPERTY_ID = 144    
            LEFT JOIN b_iblock_element_property epI ON epI.IBLOCK_ELEMENT_ID = e.ID AND epI.IBLOCK_PROPERTY_ID = 139      
            WHERE e.IBLOCK_ID = 24 AND e.ACTIVE = "Y"';
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $result = $stmt->fetchAll();
    foreach ($result as $item) {
      $name = !empty($item['NAME']) ? str_replace('"', '',  $item['NAME']) : null;
      $kpp = !empty($item['KPP']) ? $item['KPP'] : null;
      $image = !empty($item['IMAGE']) ? $item['IMAGE'] : null;

      $company = new Company();
      $company->setName($name);
      $company->setKpp($kpp);
      $company->setFile($image);
      $entityManager->persist($company);
    }
    $entityManager->flush();

    $io = new SymfonyStyle($input, $output);
    $io->success('Fill Company:' . count($result));
  }

  /**
   * @param $entityManager
   * @param $doctrine
   */
  private function fillCompanyBranch($entityManager, $doctrine, InputInterface $input, OutputInterface $output)
  {
    $conn = $entityManager->getConnection();
    $sql = 'SELECT e.ID, 
                e.NAME, 
                e.IBLOCK_ID, 
                e.ACTIVE,  
                e.CODE, 
                epK.VALUE as KPP,
                sC.ID as COMPANY_ID,
                epR.SEARCHABLE_CONTENT as REGION_NAME,
                sR.id as REGION_ID,
                epVS.VALUE as AMOUNT_STARS,
                epVAS.VALUE as ALL_AMOUNT_STAR
            FROM b_iblock_element e
            LEFT JOIN b_iblock_element_property epK ON epK.IBLOCK_ELEMENT_ID = e.ID AND epK.IBLOCK_PROPERTY_ID = 112     
            LEFT JOIN s_companies sC ON sC.kpp = epK.VALUE  
            LEFT JOIN b_iblock_section epR ON e.IBLOCK_SECTION_ID = epR.ID
            LEFT JOIN s_regions sR ON (sR.name LIKE epR.SEARCHABLE_CONTENT)            
            LEFT JOIN b_iblock_element_property epVS ON epVS.IBLOCK_ELEMENT_ID = e.ID AND epVS.IBLOCK_PROPERTY_ID = 146                
            LEFT JOIN b_iblock_element_property epVAS ON epVAS.IBLOCK_ELEMENT_ID = e.ID AND epVAS.IBLOCK_PROPERTY_ID = 131      
            WHERE e.IBLOCK_ID = 16 AND e.ACTIVE = "Y"';
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $result = $stmt->fetchAll();
    $sql = '';
    foreach ($result as $item) {
      $name = !empty($item['NAME']) ? str_replace('"', '',  $item['NAME']): null;
      $code = !empty($item['CODE']) ? $item['CODE'] : null;
      $kpp = !empty($item['KPP']) ? $item['KPP'] : null;
      $company_id = !empty($item['COMPANY_ID']) ? $item['COMPANY_ID'] : null;
      $region_id = !empty($item['REGION_ID']) ? $item['REGION_ID'] : null;
      $amountStar = !empty($item['AMOUNT_STARS']) ? (float)$item['AMOUNT_STARS'] : 0;
      $amountAllStar = !empty($item['ALL_AMOUNT_STAR']) ? (float)$item['ALL_AMOUNT_STAR'] : 0;

      $company = !empty($item['ALL_AMOUNT_STAR']) && !empty($company_id)? $doctrine->getRepository("AppBundle:Company\Company")
        ->createQueryBuilder('c')
        ->andWhere('c.id = :id')
        ->setParameter('id', $company_id)
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult() : null;
      if (!empty($company)) {
        $company->setValuation($amountAllStar);
        $entityManager->persist($company);
      }
      $sql .= "INSERT INTO s_company_branches(name, kpp, code, company_id, region_id, valuation) VALUES('$name', '$kpp', '$code', $company_id, $region_id, $amountStar); ";
    }
    $entityManager->flush();
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $io = new SymfonyStyle($input, $output);
    $io->success('Fill Company Branch:' . count($result));
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
            WHERE e.IBLOCK_ID = 13 AND e.ACTIVE = "Y"';
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $result = $stmt->fetchAll();
    foreach ($result as $item) {
      $title = !empty($item['SEARCHABLE_CONTENT']) ? trim($item['SEARCHABLE_CONTENT']) : null;
      $text = !empty($item['TEXT']) ? trim($item['TEXT']) : null;
//      $regionName  = !empty($item['REGION_NAME']) ? ($item['REGION_NAME']) : null;
      $valuation = !empty($item['VALUATION']) ? ($item['VALUATION']) : null;
//      $kpp = !empty($item['KPP']) ? ($item['KPP']) : null;
//      $userName = !empty($item['USER_NAME']) ? ($item['USER_NAME']) : null;
      $created = !empty($item['DATE_CREATE']) ? $item['DATE_CREATE'] : null;
      $date = !empty($created) ? new \DateTime($created): null;
      $reviewLetter = !empty($item['REVIEW_LETTER']) ? $item['REVIEW_LETTER'] : 0;
      $status = !empty($item['VERIFIED']) ?
        FeedbackModerationStatus::MODERATION_ACCEPTED: (!empty($item['REJECTED']) ? FeedbackModerationStatus::MODERATION_REJECTED : FeedbackModerationStatus::MODERATION_NONE);

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
      if (empty($user)) {
        $user = new User();
        $user->setLastName($lastName);
        $user->setFirstName($firstName);
        $user->setMiddleName($middleName);
        $entityManager->persist($user);
      }

      $company = !empty($item['KPP']) ? $doctrine->getRepository("AppBundle:Company\Company")
        ->createQueryBuilder('c')
        ->andWhere('c.kpp = :kpp')
        ->setParameter('kpp', $item['KPP'])
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult() : null;

      $branch = !empty($item['KPP']) ? $doctrine->getRepository("AppBundle:Company\CompanyBranch")
        ->createQueryBuilder('cb')
        ->andWhere('cb.kpp = :kpp')
        ->andWhere('cb.region = :region_id')
        ->setParameter('kpp', $item['KPP'])
        ->setParameter('region_id', $region->getId())
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult() : null;

      $feedback = new Feedback();
      $feedback->setTitle($title);
      $feedback->setText($text);
      $feedback->setRegion($region);
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
            WHERE e.IBLOCK_ID = 14 AND e.ACTIVE = "Y"';
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $result = $stmt->fetchAll();
    $sql = '';
    foreach ($result as $item) {
      $text = !empty($item['COMMENTS']) ? $item['COMMENTS']: null;
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
