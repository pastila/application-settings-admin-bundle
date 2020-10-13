<?php

namespace AppBundle\Repository\Company;

use AppBundle\Entity\Company\Feedback;
use AppBundle\Model\InsuranceCompany\FeedbackListFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @package AppBundle\Repository\Company
 */
class FeedbackRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Feedback::class);
  }

  public function countUserReviews(UserInterface $user)
  {
    return $this->createQueryBuilder('fb')
      ->select('COUNT(fb)')
      ->where('fb.author = :user')
      ->setParameter('user', $user)
      ->getQuery()
      ->getSingleScalarResult();
  }

  public function applyFilter(QueryBuilder $qb, FeedbackListFilter $filter)
  {

  }
}
