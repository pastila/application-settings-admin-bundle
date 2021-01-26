<?php

namespace AppBundle\Repository\Company;

use AppBundle\Entity\Company\InsuranceCompany;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class CompanyRepository
 * @package AppBundle\Repository\Company
 */
class InsuranceCompanyRepository extends ServiceEntityRepository
{
  /**
   * CompanyRepository constructor.
   * @param ManagerRegistry $registry
   */
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, InsuranceCompany::class);
  }

  /**
   * Отфильтровать только с активным статусом
   * @param \Doctrine\ORM\QueryBuilder $qb
   * @param string $alias
   * @return mixed
   */
  public function filterByActive($qb, $alias = 'c')
  {
    return $qb->andWhere($alias . '.status = :status')
      ->setParameter('status', CompanyStatus::ACTIVE);
  }

  /**
   * Отсортировать по имени
   * @param \Doctrine\ORM\QueryBuilder $qb
   * @param string $alias
   * @param string $sort
   * @return mixed
   */
  public function sortByName($qb, $alias = 'c', $sort = 'ASC')
  {
    return $qb->orderBy($alias . '.name', $sort);
  }

  /**
   * Вернуть запрос с отсортированным списком активных компаний
   * @param string $alias
   * @param string $sort
   * @return mixed
   */
  public function createChoiceListQueryBuilder($alias = 'c', $sort = 'ASC')
  {
    $qb = $this->createQueryBuilder($alias);
    $this->filterByActive($qb, $alias);
    $this->sortByName($qb, $alias, $sort);

    return $qb;
  }

  public function countAll()
  {
    return $this
      ->createQueryBuilder('c')
      ->select('COUNT(c)')
      ->getQuery()
      ->getSingleScalarResult();
  }

  public function countActiveAll()
  {
    return $this
      ->getActive()
      ->select('COUNT(c)')
      ->getQuery()
      ->getSingleScalarResult();
  }

  /**
   * @return \Doctrine\ORM\QueryBuilder
   */
  public function getActive()
  {
    return $this->createQueryBuilder('c')
      ->andWhere('c.published = :published')
      ->setParameter('published', true);
  }

  /**
   * @return \Doctrine\ORM\QueryBuilder
   */
  public function getWithBranchActive()
  {
    return $this->getActive()
      ->innerJoin('c.branches', 'cb', 'WITH', 'cb.published = :published');
  }
}
