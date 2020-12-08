<?php


namespace AppBundle\Exception;

use Throwable;

/**
 * Class BitrixRequestException
 * @package AppBundle\Exception
 */
class BitrixRequestException extends \Exception
{
  /**
   * @var int|null
   */
  private $httpStatusCode;

  private $response;

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
  public function getResponse(): ?mixed
  {
    return $this->response;
  }


}
