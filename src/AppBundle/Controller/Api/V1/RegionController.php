<?php

namespace AppBundle\Controller\Api\V1;

use Accurateweb\ClientApplicationBundle\DataAdapter\ClientApplicationModelTransformer;
use Accurateweb\LocationBundle\Exception\LocationServiceException;
use Accurateweb\LocationBundle\Service\Location;
use AppBundle\DataAdapter\Geo\RegionDataAdapter;
use AppBundle\Entity\Geo\Region;
use AppBundle\Form\Common\SuggestType;
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
   * @var LoggerInterface
   */
  protected $logger;
  /**
   * @var Location
   */
  private $locationService;

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
    LoggerInterface $logger,
    Location $locationService,
    RegionRepository $regionRepository,
    RegionDataAdapter $adapter
  )
  {
    $this->logger = $logger;
    $this->locationService = $locationService;
    $this->regionRepository = $regionRepository;
    $this->adapter = $adapter;
  }

  /**
   * @Route("/api/v1/regions", name="api_regions"))
   */
  public function getRegionsAction(Request $request)
  {
    $form = $this->createForm(SuggestType::class, null, [
      'csrf_protection' => false,
    ]);
    $form->submit($request->query->all());
    $query = $form->get('name')->getData();
    $regions = $this->getDoctrine()
      ->getRepository('AppBundle:Geo\Region')
      ->findByQuery($query);

    return new JsonResponse(json_encode([
      'regions' => $this->get('aw.client_application.transformer')->getClientModelCollectionData($regions, 'region'),
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

  public static function getSubscribedServices ()
  {
    return array_merge(parent::getSubscribedServices(), [
      'aw.client_application.transformer' => ClientApplicationModelTransformer::class,
    ]);
  }
}
