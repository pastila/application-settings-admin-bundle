<?php


namespace AppBundle\Helper;


use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

class DataFromBitrix
{
  /**
   * @var
   */
  private $info;

  /**
   * @var
   */
  private $res;

  /**
   * @var Request
   */
  private $request;

  /**
   * @var LoggerInterface
   */
  protected $logger;

  /**
   * DataFromBitrix constructor.
   * @param Request $request
   */
  public function __construct(
    Request $request,
    LoggerInterface $logger
  )
  {
    $this->request = $request;
    $this->logger = $logger;
  }

  /**
   *
   */
  public function getData($url)
  {
    $ch = curl_init(sprintf($url, 'http://nginx'));
    try {
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-SF-SECRET: 2851f0ae-9dc7-4a22-9283-b86abfa44900',
        'X-SF-REMOTE-ADDR: ' . $this->request->getClientIp(),
        'X-Requested-With: XmlHttpRequest'
      ));

      curl_setopt($ch, CURLOPT_COOKIE, sprintf('BX_USER_ID=%s;BITRIX_SM_LOGIN=%s;BITRIX_SM_SOUND_LOGIN_PLAYED=%s;PHPSESSID=%s',
        $this->request->cookies->get('BX_USER_ID'),
        $this->request->cookies->get('BITRIX_SM_LOGIN'),
        $this->request->cookies->get('BITRIX_SM_SOUND_LOGIN_PLAYED'),
        $this->request->cookies->get('PHPSESSID')
      ));

      curl_setopt($ch, CURLOPT_VERBOSE, true);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      $this->res = curl_exec($ch);
      $this->info = curl_getinfo($ch);
    } catch (\Exception $exception) {
      $this->logger->error(sprintf('Error in DataFromBitrix: . %s', $exception->getMessage()));
    } finally {
      curl_close($ch);
    }
  }

  /**
   * @return mixed
   */
  public function getRes()
  {
    return $this->res;
  }

  /**
   * @return mixed
   */
  public function getInfo()
  {
    return $this->info;
  }
}