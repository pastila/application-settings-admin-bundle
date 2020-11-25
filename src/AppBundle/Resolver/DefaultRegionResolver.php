<?php


namespace AppBundle\Resolver;


use Accurateweb\ApplicationSettingsAdminBundle\Model\Manager\SettingManager;
use Accurateweb\LocationBundle\LocationResolver\LocationResolverInterface;
use Accurateweb\LocationBundle\Model\ResolvedUserLocation;
use AppBundle\Entity\Geo\Region;
use AppBundle\Repository\Geo\RegionRepository;

class DefaultRegionResolver implements LocationResolverInterface
{
  private $settings;

  private $regionRepository;

  public function __construct(SettingManager $settings, RegionRepository $regionRepository)
  {
    $this->settings = $settings;
    $this->regionRepository = $regionRepository;
  }

  public function getUserLocation()
  {
    $region = null;
    $regionId = $this->settings->getValue('region_default');

    if ($regionId)
    {
      $region = $this->regionRepository->find($regionId);
    }

    if (!$region)
    {
      $region = $this->regionRepository->createQueryBuilder('r')->getQuery()->setMaxResults(1)->getOneOrNullResult();
    }

    // Ничего не нашли
    if (!$region)
    {
      return null;
    }

    $location = new ResolvedUserLocation();
    $location->setRegionName($region->getName());

    return $location;
  }

}