<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Repository\OmsChargeComplaint;

use AppBundle\Entity\OmsChargeComplaint\OmsChargeComplaint;
use AppBundle\Entity\User\User;
use AppBundle\Model\InsuranceCompany\OmsChargeComplaintFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OmsChargeComplaintRepository extends ServiceEntityRepository
{
  public function __construct (ManagerRegistry $registry, $entityClass = OmsChargeComplaint::class)
  {
    parent::__construct($registry, $entityClass);
  }

  /**
   * @param OmsChargeComplaintFilter $filter
   * @return \Doctrine\ORM\QueryBuilder
   */
  public function createQueryBuilderByFilter (OmsChargeComplaintFilter $filter)
  {
    $qb = $this->createQueryBuilder('ap');

    if ($filter->getUser() !== null)
    {
      $qb
        ->join('ap.patient', 'p')
        ->andWhere('p.user = :user')
        ->setParameter('user', $filter->getUser());
    }

    if ($filter->getStatuses() && is_array($filter->getStatuses()) && count($filter->getStatuses()))
    {
      $qb
        ->andWhere('ap.status IN (:statuses)')
        ->setParameter('statuses', $filter->getStatuses());
    }

    if ($filter->getYear())
    {
      $qb
        ->andWhere('ap.year = :year')
        ->setParameter('year', $filter->getYear());
    }

    if ($filter->getPerPage() && $filter->getPage())
    {
      $page = max(1, $filter->getPage());
      $perPage = max(1, $filter->getPerPage());
      $offset = ($page - 1) * $perPage;
      $qb->setMaxResults($perPage);
      $qb->setFirstResult($offset);
    }

    return $qb;
  }

  /**
   * @param OmsChargeComplaintFilter $filter
   * @return OmsChargeComplaint[]
   */
  public function findByFilter (OmsChargeComplaintFilter $filter)
  {
    return $this->createQueryBuilderByFilter($filter)->getQuery()->getResult();
  }
}
