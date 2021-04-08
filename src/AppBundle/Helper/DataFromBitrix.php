<?php


namespace AppBundle\Helper;


use AppBundle\Exception\BitrixRequestException;
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
   * DataFromBitrix constructor.
   * @param Request $request
   */
  public function __construct(
    Request $request
  )
  {
    $this->request = $request;
  }

  /**
   * @param $url
   * @throws BitrixRequestException
   */
  public function   getData($url)
  {
    throw new BitrixRequestException('Bitrix no more.');

//    if (!isset($_SERVER['REMOTE_ADDR']))
//    {
//      throw new BitrixRequestException('Unable to determine our client remote address.');
//    }
//
//    $ch = curl_init(sprintf($url, 'http://nginx'));
//
//    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//      'X-SF-SECRET: 2851f0ae-9dc7-4a22-9283-b86abfa44900',
//      'X-SF-REMOTE-ADDR: ' . $_SERVER['REMOTE_ADDR'],
//      'X-Requested-With: XmlHttpRequest'
//    ));
//
//    curl_setopt($ch, CURLOPT_COOKIE, sprintf('BX_USER_ID=%s;BITRIX_SM_LOGIN=%s;BITRIX_SM_SOUND_LOGIN_PLAYED=%s;PHPSESSID=%s',
//      $this->request->cookies->get('BX_USER_ID'),
//      $this->request->cookies->get('BITRIX_SM_LOGIN'),
//      $this->request->cookies->get('BITRIX_SM_SOUND_LOGIN_PLAYED'),
//      $this->request->cookies->get('PHPSESSID')
//    ));
//
//    //curl_setopt($ch, CURLOPT_VERBOSE, true);
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//
//    $this->res = curl_exec($ch);
//    $this->info = curl_getinfo($ch);
//    curl_close($ch);
//
//    if ($this->getCode() !== 200) {
//      throw new BitrixRequestException(sprintf('Failed request, http_code: %s.', $this->info['http_code']), 0,
//        $this->info['http_code'], $this->getRes());
//    }
//
//    return $this->getRes();
  }

  /**
   * @return mixed
   */
  public function getRes()
  {
    return json_decode($this->res, true);
  }

  /**
   * @return mixed
   */
  public function getInfo()
  {
    return $this->info;
  }

  /**
   * @return |null
   */
  public function getCode()
  {
    return key_exists('http_code', $this->info) ? $this->info['http_code'] : null;
  }

  /**
   * @param $attribute
   * @return mixed|null
   */
  public function getParam($attribute)
  {
    $resArray = $this->getRes();
    return key_exists($attribute, $resArray) ? $resArray[$attribute] : null;
  }
}
