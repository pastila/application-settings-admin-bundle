<?php

namespace AppBundle\Service\Sms;

use AppBundle\Exception\BitrixRequestException;
use AppBundle\Exception\SmsDataException;
use AppBundle\Exception\SmsRequestException;
use Psr\Log\LoggerInterface;

/**
 * Class SmsAtomPark
 * @package AppBundle\Service\Sms
 */
class SmsAtomPark
{
  private $privateKey;
  private $publicKey;
  private $url;
  private $version;
  private $logger;
  private $info;
  private $result;

  /**
   * SmsAtomPark constructor.
   * @param $smsPrivateKey
   * @param $smsPublicKey
   * @param $smsUrl
   * @param $smsProtocolVersion
   */
  public function __construct(
    $smsPrivateKey,
    $smsPublicKey,
    $smsUrl,
    $smsProtocolVersion,
    LoggerInterface $logger
  )
  {
    $this->privateKey = $smsPrivateKey;
    $this->publicKey = $smsPublicKey;
    $this->url = $smsUrl;
    $this->version = $smsProtocolVersion;
    $this->logger = $logger;
  }

  /**
   * @param $action
   * @param $params
   * @return mixed
   * @throws SmsDataException
   * @throws SmsRequestException
   */
  public function sendCommand($action, $params)
  {
    $params['key'] = $this->publicKey;
    $params['version'] = $this->version;
    $params['action'] = $action;
    $route ['sendTypeCountry']['211'] = "national_ua"; // Выбор маршрута для Украины
    $params ['sendOption'] = json_encode($route);
    $params = $this->getCheckSum($params);

    $this->sendDataToProvider($action, $params);
    if ($this->getCode() !== 200)
    {
      throw new SmsRequestException(sprintf('Failed request, http_code: %s.', $this->info['http_code']), 0,
        $this->info['http_code'], $this->result);
    }
    if (!$this->result)
    {
      throw new SmsDataException(sprintf('Received an empty result with params %s', json_encode($params)));
    }
    $jsonObj = json_decode($this->result, true);
    if (!$jsonObj)
    {
      throw new SmsDataException(sprintf('Received not json data from sms provider, with params %s', json_encode($params)));
    }
    if (!empty($jsonObj->error))
    {
      throw new SmsDataException(sprintf('Received error from sms provider, error: %s code: %s', $jsonObj->error, $jsonObj->code));
    }

    return $jsonObj;
  }

  /**
   * @return |null
   */
  public function getCode()
  {
    return key_exists('http_code', $this->info) ? $this->info['http_code'] : null;
  }

  /**
   * @return mixed
   */
  public function getResult()
  {
    return $this->result;
  }

  /**
   * @param $params
   * @return mixed
   */
  private function getCheckSum($params)
  {
    ksort($params);
    $sum = '';
    foreach ($params as $k => $v)
    {
      $sum .= $v;
    }
    $sum .= $this->privateKey;
    $controlSUM = md5($sum);
    $params['sum'] = $controlSUM;

    return $params;
  }

  /**
   * @param $action
   * @param $params
   */
  private function sendDataToProvider($action, $params)
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_URL, $this->url . $this->version . '/' . $action);

    $this->result = curl_exec($ch);
    $this->info = curl_getinfo($ch);
    curl_close($ch);
  }
}