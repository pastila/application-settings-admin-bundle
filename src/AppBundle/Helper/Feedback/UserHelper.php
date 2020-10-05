<?php

namespace AppBundle\Helper\Feedback;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Class UserHelper
 * @package AppBundle\Helper\Feedback
 */
class UserHelper
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
   * @return int
   * @throws \Doctrine\DBAL\DBALException
   */
  public function load()
  {
    $conn = $this->entityManager->getConnection();
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

    return count($result);
  }

  /**
   * @throws Throwable
   */
  public function check()
  {
    $this->checkFeedback();
    $this->checkComment();
  }

  /**
   * @throws Throwable
   */
  private function checkFeedback()
  {
    try {
      $sql = 'UPDATE s_company_feedbacks scf
              LEFT JOIN s_users su ON su.id = scf.user_id
              SET scf.user_id = null
              WHERE scf.user_id IS NOT NULL AND su.id IS NULL';
      $stmt = $this->entityManager->getConnection()->prepare($sql);
      $stmt->execute();
//      $sql = 'SELECT scf.ID, scf.user_id, su.id as USER_JOIN
//              FROM s_company_feedbacks scf
//              LEFT JOIN s_users su ON su.id = scf.user_id
//              WHERE scf.user_id IS NOT NULL
//              AND su.id IS NULL';
//      $stmt = $this->entityManager->getConnection()->prepare($sql);
//      $stmt->execute();
//      $result = $stmt->fetchAll();
//      foreach ($result as $item) {
//        dump($item);
//        die;
//      }
    } catch (Throwable $exception) {
      $this->logger->error(sprintf('Error update s_company_feedbacks: . %s', $exception->getMessage()));

      throw $exception;
    }
  }

  /**
   * @throws Throwable
   */
  private function checkComment()
  {
    try {
      $sql = 'UPDATE s_company_feedback_comments scfc 
              LEFT JOIN s_users su ON su.id = scfc.user_id
              SET scfc.user_id = null
              WHERE scfc.user_id IS NOT NULL AND su.id IS NULL';
      $stmt = $this->entityManager->getConnection()->prepare($sql);
      $stmt->execute();
    } catch (Throwable $exception) {
      $this->logger->error(sprintf('Error update s_company_feedback_comments: . %s', $exception->getMessage()));

      throw $exception;
    }
  }
}