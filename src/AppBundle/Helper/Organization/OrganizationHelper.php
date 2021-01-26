<?php

namespace AppBundle\Helper\Organization;

use AppBundle\Entity\Company\Company;
use AppBundle\Entity\Company\CompanyStatus;
use AppBundle\Entity\Geo\Region;
use AppBundle\Entity\Organization\Organization;
use AppBundle\Entity\Organization\OrganizationStatus;
use AppBundle\Entity\Organization\OrganizationYear;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use splitbrain\phpcli\Exception;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Throwable;

/**
 * Class OrganizationHelper
 * @package AppBundle\Helper\Organization
 */
class OrganizationHelper
{
  /**
   * @var EntityManagerInterface
   */
  private $entityManager;

  /**
   * @var LoggerInterface
   */
  protected $logger;

  /**
   * OrganizationHelper constructor.
   * @param EntityManagerInterface $entityManager
   * @param LoggerInterface $logger
   */
  public function __construct(
    EntityManagerInterface $entityManager,
    LoggerInterface $logger
  )
  {
    $this->entityManager = $entityManager;
    $this->logger = $logger;
  }

  /**
   * @param SymfonyStyle $io
   * @return int
   * @throws \Doctrine\DBAL\DBALException
   */
  public function load($io)
  {
    $conn = $this->entityManager->getConnection();
    $sql = 'SELECT  e.ID, 
                    any_value(e.NAME) as NAME,  
                    any_value(e.ACTIVE) as ACTIVE, 
                    any_value(epRegion.SEARCHABLE_CONTENT) as REGION_NAME,
                    any_value(epRegion.id) as REGION_BITRIX_ID,
                    any_value(sRegion.id) as REGION_ID,
                    any_value(epCode.VALUE) as CODE,
                    any_value(epFN.VALUE) as FULL_NAME,
                    any_value(epAddress.VALUE) as ADDRESS, 
                    any_value(epLastName.VALUE) as LAST_NAME,
                    any_value(epFirstName.VALUE) as FIRST_NAME,
                    any_value(epMiddleName.VALUE) as MIDDLE_NAME,  
                    group_concat(epYEAR.VALUE) as YEARS  
            FROM b_iblock_element e
            LEFT JOIN b_iblock_section epRegion ON epRegion.ID = e.IBLOCK_SECTION_ID
            LEFT JOIN s_regions sRegion ON sRegion.bitrix_city_hospital_id = e.IBLOCK_SECTION_ID
            LEFT JOIN b_iblock_element_property epCode ON epCode.IBLOCK_ELEMENT_ID = e.ID AND epCode.IBLOCK_PROPERTY_ID = 53    
            LEFT JOIN b_iblock_element_property epFN ON epFN.IBLOCK_ELEMENT_ID = e.ID AND epFN.IBLOCK_PROPERTY_ID = 54    
            LEFT JOIN b_iblock_element_property epAddress ON epAddress.IBLOCK_ELEMENT_ID = e.ID AND epAddress.IBLOCK_PROPERTY_ID = 55    
            LEFT JOIN b_iblock_element_property epLastName ON epLastName.IBLOCK_ELEMENT_ID = e.ID AND epLastName.IBLOCK_PROPERTY_ID = 102   
            LEFT JOIN b_iblock_element_property epFirstName ON epFirstName.IBLOCK_ELEMENT_ID = e.ID AND epFirstName.IBLOCK_PROPERTY_ID = 120       
            LEFT JOIN b_iblock_element_property epMiddleName ON epMiddleName.IBLOCK_ELEMENT_ID = e.ID AND epMiddleName.IBLOCK_PROPERTY_ID = 121  
            LEFT JOIN b_iblock_element_property epYEAR ON epYEAR.IBLOCK_ELEMENT_ID = e.ID AND epYEAR.IBLOCK_PROPERTY_ID = 132                                   
            WHERE e.IBLOCK_ID = 9 AND e.ACTIVE = "Y"
            group by e.ID;';
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $nbImported = 0; $nbTotal = 0; $nbUpdated = 0;
    while ($data = $stmt->fetch())
    {
      $nbTotal++;

      $resolver = new OptionsResolver();
      $resolver->setRequired([
        'ID',
        'NAME',
        'ACTIVE',
        'REGION_ID',
        'CODE',
        'LAST_NAME',
        'FIRST_NAME',
      ]);
      $resolver->setDefaults([
        'REGION_NAME' => null,
        'REGION_BITRIX_ID' => null,
        'FULL_NAME' => null,
        'ADDRESS' => null,
        'MIDDLE_NAME' => null,
        'YEARS' => null,
      ]);
      $resolver->setAllowedTypes('REGION_ID', ['string', 'int']);
      try
      {
        $data = $resolver->resolve($data);
      } catch (\Exception $exception)
      {
        $io->warning(sprintf('Organization import exception %s:', $exception->getMessage()));
        continue;
      }

      $organization = $this->entityManager
        ->getRepository(Organization::class)
        ->findOneBy(['bitrixId' => $data['ID']]);
      if (!$organization)
      {
        $organization = $this->entityManager
          ->getRepository(Organization::class)
          ->findOneBy(['code' => $data['CODE']]);
        if (!$organization)
        {
          $organization = new Organization();
        }
        $organization->setBitrixId($data['ID']);

        $nbImported++;
      }
      else
      {
        $nbUpdated++;
      }

      $region = $this->entityManager
        ->getRepository(Region::class)
        ->find($data['REGION_ID']);
      if (!$region)
      {
        $io->warning(sprintf('Organization %s for region %s not imported: no region found', $data['NAME'], $data['REGION_ID']));
        continue;
      }
      $yearsArray = array_unique(explode(',', $data['YEARS']));
      $years = $this->entityManager
        ->getRepository(OrganizationYear::class)
        ->createQueryBuilder('y')
        ->andWhere('y.year IN (:years)')
        ->setParameter('years', $yearsArray)
        ->getQuery()
        ->getResult();

      $status = !empty($data['ACTIVE']) ?
        ($data['ACTIVE'] === 'Y' ? OrganizationStatus::ACTIVE : OrganizationStatus::NOT_ACTIVE) :
        OrganizationStatus::NOT_ACTIVE;

      $organization->setYears($years);
      $organization->setRegion($region);
      $organization->setCode($data['CODE']);
      $organization->setName($data['NAME']);
      $organization->setStatus($status);
      $organization->setFullName($data['NAME']);
      $organization->setAddress($data['ADDRESS']);
      $organization->setLastName($data['LAST_NAME']);
      $organization->setFirstName($data['FIRST_NAME']);
      $organization->setMiddleName($data['MIDDLE_NAME']);

      $this->entityManager->persist($organization);
    }
    $this->entityManager->flush();
    $io->success(sprintf('Organization import: %s found, %s added, %s updated', $nbTotal, $nbImported, $nbUpdated));

    return $nbImported;
  }
}
