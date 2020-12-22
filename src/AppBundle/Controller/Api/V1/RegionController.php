<?php

namespace AppBundle\Controller\Api\V1;

use Accurateweb\LocationBundle\Exception\LocationServiceException;
use Accurateweb\LocationBundle\Service\Location;
use AppBundle\DataAdapter\Geo\RegionDataAdapter;
use AppBundle\Entity\Geo\Region;
use AppBundle\Repository\Geo\RegionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;

/**
 * Class RegionController
 * @package AppBundle\Controller\Api\V1
 */
class RegionController extends AbstractController
{
  /**
   * @var RegionRepository
   */
  private $regionRepository;
  /**
   * @var RegionDataAdapter
   */
  private $adapter;
  /**
   * @var LoggerInterface
   */
  protected $logger;
  /**
   * @var Location
   */
  private $locationService;

  /**
   * RegionController constructor.
   * @param RegionRepository $regionRepository
   * @param RegionDataAdapter $adapter
   */
  public function __construct(
    RegionRepository $regionRepository,
    RegionDataAdapter $adapter,
    LoggerInterface $logger,
    Location $locationService
  )
  {
    $this->regionRepository = $regionRepository;
    $this->adapter = $adapter;
    $this->logger = $logger;
    $this->locationService = $locationService;
  }

  /**
   * @Route("/api/v1/regions", name="api_regions"))
   */
  public function getRegionsAction(Request $request)
  {
    $regions = $this->regionRepository
      ->findByNameQueryBuilder($request->get('name'))
      ->getQuery()
      ->getResult();

    $data = [];
    foreach ($regions as $region)
    {
      $data[] = $this->adapter->transform($region);
    }

    return new JsonResponse(json_encode([
      'regions' => $data
    ]), 200, [], true);
  }

  /**
   * @Route("/api/v1/region", name="api_region"))
   */
  public function getRegionAction(Request $request)
  {
    /**
     * Получение региона по IP клиента
     */
    try
    {
      /**
       * @var Region
       */
      $region = $this->locationService->getLocation();
    } catch (LocationServiceException $e)
    {
      $message = 'Unable to determine user region';
      $this->get('logger')->err($message);
      return new JsonResponse($message, 404, [], true);
    }

    return new JsonResponse(json_encode([
      'region_id' => $region->getLocationId(),
      'region_name' => $region->getLocationName(),
    ]), 200, [], true);
  }
}
