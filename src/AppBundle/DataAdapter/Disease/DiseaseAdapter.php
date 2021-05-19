<?php


namespace AppBundle\DataAdapter\Disease;


use Accurateweb\ClientApplicationBundle\DataAdapter\ClientApplicationModelAdapterInterface;
use AppBundle\Entity\Disease\Disease;

class DiseaseAdapter implements ClientApplicationModelAdapterInterface
{
  /**
   * @param Disease $subject
   * @param array $options
   * @return array
   */
  public function transform ($subject, $options = array())
  {
    return [
      'id' => $subject->getId(),
      'code' => $subject->getCode(),
      'name' => $subject->getName(),
    ];
  }

  public function getModelName ()
  {
    return 'Disease';
  }

  public function supports ($subject)
  {
    return $subject instanceof Disease;
  }

  public function getName ()
  {
    return 'disease';
  }

}