<?php

namespace AppBundle\DataAdapter\OmsChargeComplaint;

use Accurateweb\ClientApplicationBundle\DataAdapter\ClientApplicationModelAdapterInterface;
use AppBundle\DataAdapter\Disease\DiseaseAdapter;
use AppBundle\DataAdapter\Geo\RegionDataAdapter;
use AppBundle\DataAdapter\Organization\MedicalOrganizationAdapter;
use AppBundle\DataAdapter\Patient\PatientDataAdapter;
use AppBundle\Entity\OmsChargeComplaint\OmsChargeComplaint;
use AppBundle\Model\File\FileStorage;

class OmsChargeComplaintAdapter implements ClientApplicationModelAdapterInterface
{
  private $regionDataAdapter;
  private $diseaseAdapter;
  private $medicalOrganizationAdapter;
  private $patientDataAdapter;
  private $omsChargeComplaintDocumentAdapter;

  public function __construct (
    RegionDataAdapter $regionDataAdapter,
    DiseaseAdapter $diseaseAdapter,
    MedicalOrganizationAdapter $medicalOrganizationAdapter,
    PatientDataAdapter $patientDataAdapter,
    OmsChargeComplaintDocumentAdapter $omsChargeComplaintDocumentAdapter
  )
  {
    $this->regionDataAdapter = $regionDataAdapter;
    $this->diseaseAdapter = $diseaseAdapter;
    $this->medicalOrganizationAdapter = $medicalOrganizationAdapter;
    $this->patientDataAdapter = $patientDataAdapter;
    $this->omsChargeComplaintDocumentAdapter = $omsChargeComplaintDocumentAdapter;
  }

  /**
   * @param OmsChargeComplaint $subject
   * @param array $options
   * @return array
   */
  public function transform ($subject, $options = array())
  {
    $documents = [];

    foreach ($subject->getDocuments() as $document)
    {
      $documents[] = $this->omsChargeComplaintDocumentAdapter->transform($document);
    }

    return [
      'id' => $subject->getId(),
      'createdAt' => $subject->getCreatedAt() ? $subject->getCreatedAt()->format('F j, Y H:i') : null,
      'sentAt' => $subject->getSentAt() ? $subject->getSentAt()->format('F j, Y H:i') : null,
      'status' => $subject->getStatus(),
      'region' => $subject->getRegion() ? $this->regionDataAdapter->transform($subject->getRegion()) : null,
      'disease' => $subject->getDisease() ? $this->diseaseAdapter->transform($subject->getDisease()) : null,
      'medicalOrganization' => $subject->getMedicalOrganization() ? $this->medicalOrganizationAdapter->transform($subject->getMedicalOrganization()) : null,
      'year' => $subject->getYear(),
      'draftStep' => $subject->getDraftStep(),
      'patient' => $subject->getPatientData() ? $this->patientDataAdapter->transform($subject->getPatientData()) : null,
      'documents' => $documents,
    ];
  }

  public function getModelName ()
  {
    return 'Appeal';
  }

  public function supports ($subject)
  {
    return $subject instanceof OmsChargeComplaint;
  }

  public function getName ()
  {
    return 'appeal';
  }

}