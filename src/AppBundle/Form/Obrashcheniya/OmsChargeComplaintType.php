<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Form\Obrashcheniya;


use AppBundle\Entity\OmsChargeComplaint\OmsChargeComplaint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OmsChargeComplaintType extends AbstractType
{
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefault('data_class', OmsChargeComplaint::class);
  }
}
