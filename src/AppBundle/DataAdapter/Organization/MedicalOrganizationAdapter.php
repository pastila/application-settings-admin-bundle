<?php

namespace AppBundle\DataAdapter\Organization;

use Accurateweb\ClientApplicationBundle\DataAdapter\ClientApplicationModelAdapterInterface;
use AppBundle\Entity\Organization\MedicalOrganization;

class MedicalOrganizationAdapter implements ClientApplicationModelAdapterInterface
{
  /**
   * @param MedicalOrganization $subject
   * @param array $options
   * @return array
   */
  public function transform ($subject, $options = array())
  {
    return [
      'code' => $subject->getCode(),
      'name' => $subject->getName(),
      'fullName' => $subject->getFullName(),
      'address' => $subject->getAddress(),
    ];
  }

  public function getModelName ()
  {
    return 'MedicalOrganization';
  }

  public function supports ($subject)
  {
    return $subject instanceof MedicalOrganization;
  }

  public function getName ()
  {
    return 'medical_organization';
  }

}