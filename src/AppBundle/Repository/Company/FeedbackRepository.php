<?php

namespace AppBundle\Repository\Company;

use AppBundle\Entity\Company\Feedback;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @package AppBundle\Repository\Company
 */
class FeedbackRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Feedback::class);
  }
}
