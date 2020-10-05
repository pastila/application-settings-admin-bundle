<?php


namespace AppBundle\Helper;


use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

class DataFromBitrixPayload
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
  public function getData($url, $payload)
  {
    $ch = curl_init(sprintf($url, 'http://nginx'));
    try {
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);

      $this->res = curl_exec($ch);
      $this->info = curl_getinfo($ch);
    }  finally {
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