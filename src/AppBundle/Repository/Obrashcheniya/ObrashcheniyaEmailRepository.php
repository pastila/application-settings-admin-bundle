<?php


namespace AppBundle\Repository\Obrashcheniya;


use AppBundle\Entity\Obrashcheniya\ObrashcheniyaEmail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class ObrashcheniyaEmailRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, ObrashcheniyaEmail::class);
  }
}