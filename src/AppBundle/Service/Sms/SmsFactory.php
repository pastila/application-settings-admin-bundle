<?php

namespace AppBundle\Service\Sms;

class SmsFactory
{
  /**
   * @var iterable|SmsInterface[]
   */
  private $smsHandlers;

  private $smsHandler;

  public function __construct (iterable $smsHandlers, $smsHandler)
  {
    $this->smsHandlers = $smsHandlers;
    $this->smsHandler = $smsHandler;
  }

  public function createSmsHandler ()
  {
    $handlers = [];

    foreach ($this->smsHandlers as $smsHandler)
    {
      $handlers[$smsHandler->getName()] = $smsHandler;
    }

    if (!isset($handlers[$this->smsHandler]))
    {
      throw new \ErrorException(sprintf('Sms handler %s not exists', $this->smsHandler));
    }

    return $handlers[$this->smsHandler];
  }
}
