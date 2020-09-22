<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class BezbahilFilterEntityType extends BezbahilFilterChoiceType
{
  public function getParent()
  {
    return EntityType::class;
  }
}
