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
   * @param Request $request
   * @return mixed|null
   */
  public function getMainMail(Request $request)
  {
    $dataFromBitrix = new DataFromBitrix($request);
    try {
      $dataFromBitrix->getData('%s/ajax/get_mess.php');
      $mainEmail = $dataFromBitrix->getParam('MAIN_EMAIL');
    }
    catch (BitrixRequestException $exception)
    {
      $this->logger->error(sprintf('Error get from getEmail: . %s', $exception->getMessage()));
      throw $exception;
    }

    return $mainEmail;
  }
}