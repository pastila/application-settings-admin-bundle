<?php

namespace AppBundle\Repository\Disease;

use AppBundle\Entity\Disease\Disease;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class DiseaseRepository extends ServiceEntityRepository
{
  public function __construct (ManagerRegistry $registry, $entityClass = Disease::class)
  {
    parent::__construct($registry, $entityClass);
  }

  /**
   * @param $query
   * @return Disease[]
   */
  public function findByQuery ($query)
  {
    return $this->createQueryBuilder('d')
      ->where('d.name LIKE :query')
      ->setParameter('query', '%' . $query . '%')
      ->getQuery()
      ->getResult();
  }
}
