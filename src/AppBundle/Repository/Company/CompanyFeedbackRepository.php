<?php

namespace AppBundle\Repository\Company;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class CompanyFeedbackRepository
 * @package AppBundle\Repository\Company
 */
class CompanyFeedbackRepository extends ServiceEntityRepository
{
  /**
   * CompanyRepository constructor.
   * @param ManagerRegistry $registry
   * @param string $entityClass
   */
  public function __construct (ManagerRegistry $registry, $entityClass = 'AppBundle\Entity\Company\CompanyFeedback')
  {
    parent::__construct($registry, $entityClass);
  }

  /**
   * @return array
   */
  public function findAvailableCompanyFeedback ()
  {
    return $this->findAll();
  }
}