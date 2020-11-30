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

  public function getFileQuery($user, $file)
  {
    return $this
      ->createQueryBuilder('o_f')
      ->andWhere('o_f.file LIKE :file')
      ->andWhere('o_f.author = :author')
      ->setParameter('author', $user)
      ->setParameter('file', '%' . $file );
  }
}