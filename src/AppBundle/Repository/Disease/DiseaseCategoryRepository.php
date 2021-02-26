<?php

namespace AppBundle\Repository\Disease;

use AppBundle\Entity\Disease\DiseaseCategory;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

/**
 * Class DiseaseCategoryRepository
 * @package AppBundle\Repository\Disease
 */
class DiseaseCategoryRepository extends NestedTreeRepository
{
  /**
   * DiseaseCategoryRepository constructor.
   * @param EntityManagerInterface $manager
   */
  public function __construct(EntityManagerInterface $manager)
  {
    parent::__construct($manager, $manager->getClassMetadata(DiseaseCategory::class));
  }
}
