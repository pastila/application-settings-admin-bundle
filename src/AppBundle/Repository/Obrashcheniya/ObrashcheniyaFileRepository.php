<?php


namespace AppBundle\Repository\Obrashcheniya;


use AppBundle\Entity\Company\CompanyStatus;
use AppBundle\Entity\Obrashcheniya\ObrashcheniyaFile;
use AppBundle\Entity\Obrashcheniya\ObrashcheniyaFileType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class ObrashcheniyaFileRepository
 * @package AppBundle\Repository\Obrashcheniya
 */
class ObrashcheniyaFileRepository extends ServiceEntityRepository
{
  /**
   * ObrashcheniyaFileRepository constructor.
   * @param ManagerRegistry $registry
   */
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, ObrashcheniyaFile::class);
  }

  /**
   * @param $bitrixId
   * @param null $imageNumber
   * @param null $user
   * @param string $type
   * @return \Doctrine\ORM\QueryBuilder
   */
  public function createFileQueryBuilder($bitrixId, $imageNumber = null, $user = null, $type = ObrashcheniyaFileType::REPORT)
  {
    $query = $this
      ->createQueryBuilder('o_f')
      ->andWhere('o_f.bitrixId = :bitrixId')
      ->setParameter('bitrixId', $bitrixId )
      ->orderBy('o_f.id', 'DESC');
    if ($imageNumber)
    {
      $query
        ->andWhere('o_f.type = :type')
        ->setParameter('type', ObrashcheniyaFileType::ATTACH)
        ->andWhere('o_f.imageNumber = :imageNumber')
        ->setParameter('imageNumber', $imageNumber);
    } else {
      $query
        ->andWhere('o_f.type = :type')
        ->setParameter('type', $type);
    }
    if (!$user)
    {
      return $query;
    }

    return $query
      ->andWhere('o_f.author = :author')
      ->setParameter('author', $user);
  }
}