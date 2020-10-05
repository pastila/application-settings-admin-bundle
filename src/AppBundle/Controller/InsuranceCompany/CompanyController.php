<?php

namespace AppBundle\Controller\InsuranceCompany;

use AppBundle\Helper\DataFromBitrix;
use AppBundle\Helper\DataFromBitrixPayload;
use AppBundle\Repository\Company\FeedbackRepository;
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
   * @param null $branch_id
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function _logoPanelAction(Request $request, $logo_id, $name)
  {
    $payload = ([
      'logo_id' => $logo_id
    ]);
    $dataFromBitrix = new DataFromBitrixPayload($request, $this->logger);
    $dataFromBitrix->getData('%s/ajax/get_resize_image.php', $payload);

    $src = null;
    if (!empty($dataFromBitrix->getInfo()['http_code']) && $dataFromBitrix->getInfo()['http_code'] === 200) {
      $res = json_decode($dataFromBitrix->getRes(), true);
      $src = !empty($res['src']) ? $res['src'] : null;
    }

    return $this->render('Company/_logo.html.twig', [
      'src' => $src,
      'name' => $name
    ]);
  }
}