<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Repository\OmsChargeComplaint;


use Doctrine\ORM\EntityManagerInterface;

/**
 * Class OmsChargeComplaintRepository
 * @package AppBundle\Repository\OmsChargeComplaint
 *
 * @method findOneBy(array $array)
 */
class OmsChargeComplaintRepository
{
  private $repository;

  public function __construct(EntityManagerInterface $entityManager)
  {
    $this->repository = $entityManager->getRepository('AppBundle:OmsChargeComplaint\OmsChargeComplaint');
  }

  public function __call($name, $arguments)
  {
    return call_user_func_array([$this->repository, $name], $arguments);
  }
}
