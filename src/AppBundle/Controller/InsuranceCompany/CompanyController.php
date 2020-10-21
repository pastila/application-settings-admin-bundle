<?php

namespace AppBundle\Controller\InsuranceCompany;

use AppBundle\Exception\BitrixRequestException;
use AppBundle\Helper\DataFromBitrix;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CompanyController
 * @package AppBundle\Controller\InsuranceCompany
 */
class CompanyController extends AbstractController
{
  /**
   * @var LoggerInterface
   */
  protected $logger;

  /**
   * CompanyController constructor.
   * @param LoggerInterface $logger
   */
  public function __construct(
    LoggerInterface $logger
  )
  {
    $this->logger = $logger;
  }

  /**
   * @param Request $request
   * @param $logo_id
   * @param $name
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function _logoPanelAction(Request $request, $logo_id, $name)
  {
    $payload = '?logo_id=' . $logo_id;
    $dataFromBitrix = new DataFromBitrix($request);
    $src = null;

    try
    {
      $dataFromBitrix->getData('%s/ajax/get_resize_image.php' . $payload);
      $src = $dataFromBitrix->getParam('src');
    }
    catch (BitrixRequestException $exception)
    {
      $this->logger->error(sprintf('Error get from bitrix logo: . %s', $exception->getMessage()));
    }

    return $this->render('Company/_logo.html.twig', [
      'src' => $src,
      'name' => $name
    ]);
  }
}