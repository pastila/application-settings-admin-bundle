<?php


namespace AppBundle\Form\DataTransformer;

use AppBundle\Entity\Organization\OrganizationYear;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Collections\ArrayCollection;

class YearsToCollectionTransformer implements DataTransformerInterface
{
  private $manager;
  private $organization;

  public function __construct(
    EntityManagerInterface $manager,
    $organization
  )
  {
    $this->manager = $manager;
    $this->organization = $organization;
  }

  public function transform($years)
  {
    return $years !== null ? $years->getValues() : [];
  }

  public function reverseTransform($years)
  {
    $yearCollection = new ArrayCollection();
    $tagsRepository = $this->manager
      ->getRepository(OrganizationYear::class);

    foreach ($years as $year)
    {
      $yearInRepo = $tagsRepository->findOneBy([
        'organization' => $this->organization,
        'year' => $year
      ]);

      if ($yearInRepo !== null)
      {
        $yearCollection->add($yearInRepo);
      } else
      {
        $yearEntity = new OrganizationYear();
        $yearEntity->setOrganization($this->organization);
        $yearEntity->setYear($year);
        $yearCollection->add($yearEntity);
      }
    }

    return $yearCollection;
  }
}