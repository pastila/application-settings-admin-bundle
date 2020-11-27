<?php


namespace AppBundle\Helper;

use AppBundle\Exception\BitrixRequestException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class GetMessFromBitrix
 * @package AppBundle\Helper
 */
class GetMessFromBitrix
{
  /**
   * @var LoggerInterface
   */
  protected $logger;

  /**
   * GetMessFromBitrix constructor.
   * @param LoggerInterface $logger
   */
  public function __construct(
    LoggerInterface $logger
  )
  {
    $this->logger = $logger;
  }

  /**
   * @param $request
   * @return array|mixed
   * @throws BitrixRequestException
   */
  public function getParams($request)
  {
    $params = [];
    $dataFromBitrix = new DataFromBitrix($request);
    try {
      $dataFromBitrix->getData('%s/ajax/get_mess.php');
      $params = $dataFromBitrix->getRes();
    }
    catch (BitrixRequestException $exception)
    {
      $this->logger->error(sprintf('Error get from getParams: . %s', $exception->getMessage()));
      throw $exception;
    }

    return $params;
  }
}