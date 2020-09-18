<?php

namespace AppBundle\Repository\Company;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class CompanyRepository
 * @package AppBundle\Repository\Company
 */
class CompanyRepository extends ServiceEntityRepository
{
  /**
   * CompanyRepository constructor.
   * @param ManagerRegistry $registry
   * @param string $entityClass
   */
  public function __construct (ManagerRegistry $registry, $entityClass = 'AppBundle\Entity\Company\Company')
  {
    parent::__construct($registry, $entityClass);
  }

  /**
   * @return array
   */
  public function findAvailableCompany ()
  {
    return $this->findAll();
  }
}