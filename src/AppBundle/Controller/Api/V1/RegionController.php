<?php

namespace AppBundle\Controller\Api\V1;

use AppBundle\DataAdapter\Geo\RegionDataAdapter;
use AppBundle\Repository\Geo\RegionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
   * RegionController constructor.
   * @param RegionRepository $regionRepository
   * @param RegionDataAdapter $adapter
   */
  public function __construct(
    RegionRepository $regionRepository,
    RegionDataAdapter $adapter
  )
  {
    $this->regionRepository = $regionRepository;
    $this->adapter = $adapter;
  }

  /**
   * @Route("/api/v1/regions", name="api_regions"))
   */
  public function getRegionsAction(Request $request)
  {
    $regions = $this->regionRepository
      ->findAll();

    $data = [];
    foreach ($regions as $region)
    {
      $data[] = $this->adapter->transform($region);
    }

    return new JsonResponse(json_encode([
      'regions' => $data
    ]), 200, [], true);
  }
}
