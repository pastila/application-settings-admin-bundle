<?php

namespace AppBundle\Repository\Organization;

use AppBundle\Entity\Organization\MedicalOrganization;
use AppBundle\Model\Filter\MedicalOrganizationFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class OrganizationRepository
 * @package AppBundle\Repository\Organization
 */
class MedicalOrganizationRepository extends ServiceEntityRepository
{
  /**
   * OrganizationRepository constructor.
   * @param ManagerRegistry $registry
   */
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, MedicalOrganization::class);
  }

  /**
   * @param MedicalOrganizationFilter $filter
   * @return \Doctrine\ORM\QueryBuilder
   */
  public function createQueryBuilderByFilter (MedicalOrganizationFilter $filter)
  {
    $qb = $this->createQueryBuilder('o');

    if ($filter->getRegion())
    {
      $qb
        ->andWhere('o.region = :region')
        ->setParameter('region', $filter->getRegion());
    }

    if ($filter->getYear())
    {
      $qb
        ->join('o.years', 'y')
        ->andWhere('y.year = :year')
        ->setParameter('year', $filter->getYear());
    }

    if ($filter->getQuery())
    {
      $qb
        ->andWhere('(o.name LIKE :query OR o.fullName LIKE :query)')
        ->setParameter('query', '%' . $filter->getQuery() . '%');
    }

    return $qb;
  }

  /**
   * @param MedicalOrganizationFilter $filter
   * @return MedicalOrganization[]
   */
  public function findByFilter (MedicalOrganizationFilter $filter)
  {
    return $this->createQueryBuilderByFilter($filter)->getQuery()->getResult();
  }
}
