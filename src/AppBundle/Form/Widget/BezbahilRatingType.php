<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Form\Widget;


use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class BezbahilRatingType extends IntegerType
{
  public function getBlockPrefix()
  {
    return 'bezbahil_rating';
  }
}
