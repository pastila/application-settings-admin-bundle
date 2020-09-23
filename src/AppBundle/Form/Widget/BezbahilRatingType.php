<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Form\Widget;


use Symfony\Component\Form\AbstractType;

class BezbahilRatingType extends AbstractType
{
  public function getBlockPrefix()
  {
    return 'bezbahil_rating';
  }
}
