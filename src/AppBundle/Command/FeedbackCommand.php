<?php

namespace AppBundle\Command;

use AppBundle\Entity\Company\Company;
use AppBundle\Entity\Company\CompanyBranch;
use AppBundle\Entity\Company\Feedback;
use AppBundle\Entity\Geo\Region;
use AppBundle\Entity\User\User;
use AppBundle\Model\Checkout\TransactionStatus;
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
   * @return int|void|null
   */
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
    $doctrine = $this->getContainer()->get('doctrine');

    $this->fillUser($entityManager);
    $this->fillRegion($entityManager);
    $this->fillCompany($entityManager);
    $this->fillCompanyBranch($entityManager, $doctrine);
    $this->fillFeedback($entityManager, $doctrine);
  }

  /**
   * @param $entityManager
   */
  private function fillRegion($entityManager)
  {
    $conn = $entityManager->getConnection();
    $stmt = $conn->prepare('SELECT * FROM b_iblock_section WHERE IBLOCK_ID = 16 AND ACTIVE = "Y"');
    $stmt->execute();

    $result = $stmt->fetchAll();
    foreach ($result as $item) {
      $name = !empty($item['SEARCHABLE_CONTENT']) ? $item['SEARCHABLE_CONTENT'] : null;
      $code = !empty($item['CODE']) ? $item['CODE'] : null;

      $region = new Region();
      $region->setName($name);
      $region->setCode($code);
      $entityManager->persist($region);
    }

    $entityManager->flush();
    $entityManager->commit();
  }

  /**
   * @param $entityManager
   */
  private function fillUser($entityManager)
  {
    $conn = $entityManager->getConnection();
    $stmt = $conn->prepare('SELECT * FROM b_user');
    $stmt->execute();

    $result = $stmt->fetchAll();
    foreach ($result as $item) {
      $login = !empty($item['LOGIN']) ? $item['LOGIN'] : null;
      $lastName = !empty($item['LAST_NAME']) ? $item['LAST_NAME'] : null;
      $firstName = !empty($item['NAME']) ? $item['NAME'] : null;
      $middleName = !empty($item['SECOND_NAME']) ? $item['SECOND_NAME'] : null;

      $user = new User();
      $user->setLogin($login);
      $user->setLastName($lastName);
      $user->setFirstName($firstName);
      $user->setMiddleName($middleName);
      $entityManager->persist($user);
    }

    $entityManager->flush();
    $entityManager->commit();
  }

  /**
   * @param $entityManager
   */
  private function fillCompany($entityManager)
  {
    $conn = $entityManager->getConnection();
    $sql = 'SELECT e.ID, e.NAME, e.IBLOCK_ID, e.ACTIVE, epKpp.VALUE as KPP
            FROM b_iblock_element e
            LEFT JOIN b_iblock_element_property epKpp ON epKpp.IBLOCK_ELEMENT_ID = e.ID AND epKpp.IBLOCK_PROPERTY_ID = 144     
            WHERE e.IBLOCK_ID = 24 AND e.ACTIVE = "Y"';
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $result = $stmt->fetchAll();
    foreach ($result as $item) {
      $name = !empty($item['NAME']) ? $item['NAME'] : null;
      $kpp = !empty($item['KPP']) ? $item['KPP'] : null;
      $company = new Company();
      $company->setName($name);
      $company->setKpp($kpp);
      $entityManager->persist($company);
    }

    $entityManager->flush();
    $entityManager->commit();
  }

  /**
   * @param $entityManager
   * @param $doctrine
   */
  private function fillCompanyBranch($entityManager, $doctrine)
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
                sR.id as REGION_ID
            FROM b_iblock_element e
            LEFT JOIN b_iblock_element_property epK ON epK.IBLOCK_ELEMENT_ID = e.ID AND epK.IBLOCK_PROPERTY_ID = 112     
            LEFT JOIN s_companies sC ON sC.kpp = epK.VALUE  
            LEFT JOIN b_iblock_section epR ON e.IBLOCK_SECTION_ID = epR.ID
            LEFT JOIN s_regions sR ON (sR.name LIKE epR.SEARCHABLE_CONTENT)
            WHERE e.IBLOCK_ID = 16 AND e.ACTIVE = "Y"';
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $result = $stmt->fetchAll();
    $sql = '';
    foreach ($result as $item) {
      $name = !empty($item['NAME']) ? $item['NAME'] : null;
      $code = !empty($item['CODE']) ? $item['CODE'] : null;
      $kpp = !empty($item['KPP']) ? $item['KPP'] : null;
      $company_id = !empty($item['COMPANY_ID']) ? $item['COMPANY_ID'] : null;
      $region_id = !empty($item['REGION_ID']) ? $item['REGION_ID'] : null;

      $sql .= "INSERT INTO s_company_branches(name, kpp, code, company_id, region_id) VALUES('$name', '$kpp', '$code', $company_id, $region_id); ";
    }
    $stmt = $conn->prepare($sql);
    $stmt->execute();
  }

  /**
   * @param $entityManager
   * @param $doctrine
   */
  private function fillFeedback($entityManager, $doctrine)
  {
    $conn = $entityManager->getConnection();
    $sql = 'SELECT  e.ID, 
                    e.SEARCHABLE_CONTENT, 
                    e.DATE_CREATE, 
                    sR.NAME as REGION_NAME, 
                    sr.id as REGION_ID, 
                    epT.VALUE as TEXT, 
                    epV.VALUE as VALUATION, 
                    epK.VALUE as KPP, 
                    epU.VALUE as USER_NAME
            FROM b_iblock_element e 
            LEFT JOIN b_iblock_element_property epR ON epR.IBLOCK_ELEMENT_ID = e.ID AND epR.IBLOCK_PROPERTY_ID = 60    
            LEFT JOIN b_iblock_section sR ON sR.ID = epR.VALUE  
            LEFT JOIN s_regions sr ON (sr.name LIKE sR.SEARCHABLE_CONTENT)
                        
            LEFT JOIN b_iblock_element_property epT ON epT.IBLOCK_ELEMENT_ID = e.ID AND epT.IBLOCK_PROPERTY_ID = 61    
            LEFT JOIN b_iblock_element_property epV ON epV.IBLOCK_ELEMENT_ID = e.ID AND epV.IBLOCK_PROPERTY_ID = 62  
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
      $date = !empty($created) ? DateTime::createFromFormat('Y-m-d H:m:s', $created) : null;

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

      $branch = !empty($item['KPP']) ? $doctrine->getRepository("AppBundle:Company\CompanyBranch")
        ->createQueryBuilder('c')
        ->andWhere('c.kpp = :kpp')
        ->setParameter('kpp', $item['KPP'])
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult() : null;

      $feedback = new Feedback();
      $feedback->setTitle($title);
      $feedback->setText($text);
      $feedback->setRegion($region);
      $feedback->setUser($user);
      $feedback->setBranch($branch);
      $feedback->setValuation($valuation);
      $feedback->setCreatedAt($date);
      $feedback->setUpdatedAt($date);
      $entityManager->persist($feedback);
    }
    $entityManager->flush();
    $entityManager->commit();
  }
}
