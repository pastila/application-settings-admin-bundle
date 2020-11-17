<?php

namespace AppBundle\Repository\ContactUs;

use AppBundle\Entity\ContactUs\ContactUs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class ContactUsRepository
 * @package AppBundle\Repository\ContactUs
 */
class ContactUsRepository extends ServiceEntityRepository
{
  /**
   * ContactUsRepository constructor.
   * @param ManagerRegistry $registry
   */
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, ContactUs::class);
  }
}
