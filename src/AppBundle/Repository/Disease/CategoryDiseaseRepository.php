<?php

namespace AppBundle\Repository\Disease;

use AppBundle\Entity\Disease\CategoryDisease;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

/**
 * Class CategoryDiseaseRepository
 * @package AppBundle\Repository\Disease
 */
class CategoryDiseaseRepository extends NestedTreeRepository
{
  /**
   * CategoryDiseaseRepository constructor.
   * @param EntityManagerInterface $manager
   */
  public function __construct(EntityManagerInterface $manager)
  {
    parent::__construct($manager, $manager->getClassMetadata(CategoryDisease::class));
  }
}
