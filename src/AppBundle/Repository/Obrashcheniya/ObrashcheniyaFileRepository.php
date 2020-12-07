<?php


namespace AppBundle\Repository\Obrashcheniya;


use AppBundle\Entity\Company\CompanyStatus;
use AppBundle\Entity\Obrashcheniya\ObrashcheniyaFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class ObrashcheniyaFileRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, ObrashcheniyaFile::class);
  }

  /**
   * @param $bitrixId
   * @param null $user
   * @return \Doctrine\ORM\QueryBuilder
   */
  public function getFileQuery($bitrixId, $user = null)
  {
    $query = $this
      ->createQueryBuilder('o_f')
      ->andWhere('o_f.bitrixId = :bitrixId')
      ->setParameter('bitrixId', $bitrixId );
    if (!$user)
    {
      return $query;
    }

    return $query
      ->andWhere('o_f.author = :author')
      ->setParameter('author', $user);
  }
}