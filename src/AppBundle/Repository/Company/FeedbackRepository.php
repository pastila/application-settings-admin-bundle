<?php

namespace AppBundle\Repository\Company;

use AppBundle\Entity\Company\Feedback;
use AppBundle\Entity\Company\FeedbackModerationStatus;
use AppBundle\Model\Filter\FeedbackFilter;
use AppBundle\Model\InsuranceCompany\FeedbackListFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class FeedbackRepository
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

  /**
   * @param QueryBuilder $qb
   * @return QueryBuilder
   */
  public function joinCompanyBranchActive(QueryBuilder $qb)
  {
    return $qb
      ->innerJoin('rv.branch', 'rvb')
      ->innerJoin('rvb.company', 'rvc')
      ->andWhere('rvb.published = :published')
      ->andWhere('rvc.published = :published')
      ->setParameter('published', true);
  }

  /**
   * @param QueryBuilder $qb
   * @return QueryBuilder
   */
  public function joinCommentsCitations(QueryBuilder $qb)
  {
    return $qb
      ->leftJoin('rv.comments', 'rvct')
      ->leftJoin('rvct.citations', 'rvctcs');
  }

  /**
   * @param QueryBuilder $qb
   * @return QueryBuilder
   */
  public function filterAccepted(QueryBuilder $qb)
  {
    return $qb
      ->andWhere('rv.moderationStatus = :moderationStatus')
      ->setParameter('moderationStatus', FeedbackModerationStatus::MODERATION_ACCEPTED);
  }

  /**
   * @return QueryBuilder
   */
  public function getFeedbackActive()
  {
    $qb = $this->createQueryBuilder('rv');
    $this->filterAccepted($qb);
    $this->joinCompanyBranchActive($qb);
    $this->joinCommentsCitations($qb);

    return $qb;
  }

  public function createQueryBuilderByFilter (FeedbackFilter $filter)
  {
    $qb = $this->createQueryBuilder('f');

    if ($filter->getAuthor())
    {
      $qb
        ->andWhere('f.author = :author')
        ->setParameter('author', $filter->getAuthor());
    }

    if ($filter->getModerationStatus() !== null)
    {
      $qb
        ->andWhere('f.moderationStatus = :status')
        ->setParameter('status', $filter->getModerationStatus());
    }

    if ($filter->getModerationStatuses() && is_array($filter->getModerationStatuses()) && count($filter->getModerationStatuses()))
    {
      $qb
        ->andWhere('f.moderationStatus IN (:statuses)')
        ->setParameter('statuses', $filter->getModerationStatuses());
    }

    return $qb;
  }
}
