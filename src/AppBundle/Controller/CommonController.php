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
  protected $logger;
  private $locationService;

  /**
   * CommonController constructor.
   * @param LoggerInterface $logger
   * @param Location $locationService
   */
  public function __construct(
    LoggerInterface $logger,
    Location $locationService
  )
  {
    $this->logger = $logger;
    $this->locationService = $locationService;
  }

  /**
   * @param Request $request
   * @return \Symfony\Component\HttpFoundation\Response
   * @throws LocationServiceException
   */
  public function _locationAction(Request $request)
  {
    /**
     * Получение региона по IP клиента
     */
    try
    {
      $region = $this->locationService->getLocation();
    } catch (LocationServiceException $e)
    {
      $this->get('logger')->err('Unable to determine user region.');
      throw $e;
    }

    return $this->render('Common/_location.html.twig', [
      'region' => $region,
    ]);
  }
}