<?php

namespace AppBundle\Helper\Organization;

use AppBundle\Entity\Company\Company;
use AppBundle\Entity\Company\CompanyStatus;
use AppBundle\Entity\Organization\Organization;
use AppBundle\Entity\Organization\OrganizationStatus;
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
                    any_value(epCode.VALUE) as CODE,
                    any_value(epFN.VALUE) as FULL_NAME,
                    any_value(epAddress.VALUE) as ADDRESS, 
                    any_value(epLastName.VALUE) as LAST_NAME,
                    any_value(epFirstName.VALUE) as FIRST_NAME,
                    any_value(epMiddleName.VALUE) as MIDDLE_NAME,  
                    group_concat(epYEAR.VALUE) as YEARS  
            FROM b_iblock_element e
            LEFT JOIN b_iblock_element_property epCode ON epCode.IBLOCK_ELEMENT_ID = e.ID AND epCode.IBLOCK_PROPERTY_ID = 53    
            LEFT JOIN b_iblock_element_property epFN ON epFN.IBLOCK_ELEMENT_ID = e.ID AND epFN.IBLOCK_PROPERTY_ID = 54    
            LEFT JOIN b_iblock_element_property epAddress ON epAddress.IBLOCK_ELEMENT_ID = e.ID AND epAddress.IBLOCK_PROPERTY_ID = 55    
            LEFT JOIN b_iblock_element_property epLastName ON epLastName.IBLOCK_ELEMENT_ID = e.ID AND epLastName.IBLOCK_PROPERTY_ID = 102   
            LEFT JOIN b_iblock_element_property epFirstName ON epFirstName.IBLOCK_ELEMENT_ID = e.ID AND epFirstName.IBLOCK_PROPERTY_ID = 120       
            LEFT JOIN b_iblock_element_property epMiddleName ON epMiddleName.IBLOCK_ELEMENT_ID = e.ID AND epMiddleName.IBLOCK_PROPERTY_ID = 121  
            LEFT JOIN b_iblock_element_property epYEAR ON epYEAR.IBLOCK_ELEMENT_ID = e.ID AND epYEAR.IBLOCK_PROPERTY_ID = 132                              
            WHERE e.IBLOCK_ID = 9
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
        'CODE',
        'FULL_NAME',
        'ADDRESS',
        'LAST_NAME',
        'FIRST_NAME',
        'MIDDLE_NAME',
        'YEARS',
      ]);
      $resolver->setDefaults([
        'FULL_NAME' => null,
        'ADDRESS' => null,
        'MIDDLE_NAME' => null,
        'YEARS' => null,
      ]);

      try
      {
        $data = $resolver->resolve($data);
      } catch (\Exception $exception)
      {
        $io->error(sprintf('Organization import exception %s:', $exception->getMessage()));
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
      $status = !empty($data['ACTIVE']) ?
        ($data['ACTIVE'] === 'Y' ? OrganizationStatus::ACTIVE : OrganizationStatus::NOT_ACTIVE) :
        OrganizationStatus::NOT_ACTIVE;

      $organization->setName($data['NAME']);
      $organization->setStatus($status);
      $organization->setNameFull($data['NAME']);
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
