<?php


namespace AppBundle\Controller;


use Accurateweb\LocationBundle\Exception\LocationServiceException;
use Accurateweb\LocationBundle\Service\Location;
use AppBundle\Exception\BitrixRequestException;
use AppBundle\Helper\DataFromBitrix;
use AppBundle\Repository\Geo\RegionRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommonController
 * @package AppBundle\Controller
 */
class CommonController extends AbstractController
{
  private $logger;
  private $locationService;
  private $regionRepository;

  /**
   * CommonController constructor.
   * @param LoggerInterface $logger
   * @param Location $locationService
   */
  public function __construct(
    LoggerInterface $logger,
    RegionRepository $regionRepository,
    Location $locationService
  )
  {
    $this->logger = $logger;
    $this->locationService = $locationService;
    $this->regionRepository = $regionRepository;
  }

  /**
   * @param Request $request
   * @return \Symfony\Component\HttpFoundation\Response
   * @throws LocationServiceException
   */
  public function _headerAction(Request $request)
  {
    /**
     * Получение региона по IP клиента
     */
    try
    {
      $region = $this->locationService->getLocation();
    } catch (LocationServiceException $e)
    {
      $this->logger->err('Unable to determine user region.');
      throw $e;
    }

    $regions = $this->regionRepository
      ->createQueryBuilder('r')
      ->orderBy('r.name')
      ->getQuery()
      ->getResult();

    return $this->render('Common/_header.html.twig', [
      'region' => $region,
      'regions' => $regions,
      'isHomepage' => $request->get('isHomepage', false)
    ]);
  }
}