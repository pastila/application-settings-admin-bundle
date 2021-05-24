<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Service\Obrashcheniya;


use AppBundle\Entity\OmsChargeComplaint\OmsChargeComplaint;
use AppBundle\Repository\OmsChargeComplaint\OmsChargeComplaintRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OmsChargeComplaintSessionResolver
{
  private $omsChargeComplaintRepository;

  private $session;

  public function __construct(
    OmsChargeComplaintRepository $omsChargeComplaintRepository,
    SessionInterface $session
  )
  {
    $this->omsChargeComplaintRepository = $omsChargeComplaintRepository;
    $this->session = $session;
  }

  /**
   * @return OmsChargeComplaint
   */
  public function resolve()
  {
    $omsChargeComplaint = $this->omsChargeComplaintRepository->findOneBy([
      'id' => $this->session->get('oms_charge_complaint_id'),
    ]);

    if ($omsChargeComplaint)
    {
      return $omsChargeComplaint;
    }

    return new OmsChargeComplaint();
  }
}
