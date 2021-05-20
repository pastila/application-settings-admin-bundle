<?php

namespace AppBundle\Service\Obrashcheniya;

use AppBundle\Entity\OmsChargeComplaint\OmsChargeComplaint;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OmsChargeComplaintSessionPersister
{
  private $session;

  public function __construct (SessionInterface $session)
  {
    $this->session = $session;
  }

  public function persist(OmsChargeComplaint $omsChargeComplaint)
  {
    if (!$omsChargeComplaint->getId())
    {
      throw new InvalidArgumentException('OmsChargeComplaint must be saved to database before persisting to session');
    }

    $this->session->set('oms_charge_complaint_id', $omsChargeComplaint->getId());
  }
}
