<?php

namespace AppBundle\Repository\Geo;

use Accurateweb\LocationBundle\Model\ResolvedUserLocation;
use Accurateweb\LocationBundle\Model\UserLocationRepositoryInterface;
use AppBundle\Entity\Geo\Region;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @package AppBundle\Repository\Geo
 */
class RegionRepository extends ServiceEntityRepository implements UserLocationRepositoryInterface
{
  /**
   * CompanyRepository constructor.
   * @param ManagerRegistry $registry
   * @param string $entityClass
   */
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Region::class);
  }

  public function findByResolvedLocation(ResolvedUserLocation $resolvedUserLocation)
  {
    if (!$resolvedUserLocation->getRegionName())
    {
      return null;
    }

    return $this->createQueryBuilder('r')
      ->where('r.name LIKE :n')
      ->setParameter('n', '%'.$resolvedUserLocation->getRegionName().'%')
      ->getQuery()
      ->setMaxResults(1)
      ->getOneOrNullResult();
  }

  /**
   * Получить запрос QueryBuilder,
   * который производит запрос для получения списка регионов в которых присутствуеют филиалы СМО
   * отсортированного по названию регионов
   * @param $companyId - ID в InsuranceCompany, по которой будут получены все регионы по филиалам указанной компании
   * @return \Doctrine\ORM\QueryBuilder
   */
  public function getRegionsInCompanyQueryBuilder($companyId)
  {
    return $this->createQueryBuilder('r')
    ->leftJoin('r.branches', 'cb')
    ->leftJoin('cb.company', 'c')
    ->where('cb.company = :company')
    ->andWhere('cb.published = :published')
    ->andWhere('c.published = :published')
    ->setParameter('company', $companyId)
    ->setParameter('published', true);
  }
}
