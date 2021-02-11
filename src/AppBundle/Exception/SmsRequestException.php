<?php


namespace AppBundle\Exception;

use Throwable;

/**
 * Class SmsRequestException
 * @package AppBundle\Exception
 */
class SmsRequestException extends \Exception
{
  private $httpStatusCode;
  private $response;

  /**
   * SmsRequestException constructor.
   * @param string $message
   * @param int $code
   * @param null $httpStatusCode
   * @param null $response
   * @param Throwable|null $previous
   */
  public function __construct($message = "", $code = 0, $httpStatusCode = null, $response = null,
    Throwable $previous = null)
  {
    parent::__construct($message, $code, $previous);

    $this->httpStatusCode = $httpStatusCode;
    $this->response = $response;
  }

  /**
   * @return int|null
   */
  public function getHttpStatusCode(): ?int
  {
    return $this->httpStatusCode;
  }

  /**
   * @return mixed|null
   */
  public function getResponse()
  {
    return $this->response;
  }
}
