<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Entity\OmsChargeComplaint;

use AppBundle\Entity\Disease\Disease;
use AppBundle\Entity\Geo\Region;
use AppBundle\Entity\Organization\MedicalOrganization;
use AppBundle\Entity\User\Patient;
use AppBundle\Helper\Year\Year;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Жалоба на взимание денежных средств
 *
 * @ORM\Entity()
 * @ORM\Table(name="s_oms_charge_complaints")
 */
class OmsChargeComplaint
{
  const STATUS_DRAFT = 0;
  const STATUS_CREATED = 1;
  const STATUS_SENT = 2;

  /**
   * @var int
   *
   * @ORM\Column(type="integer")
   * @ORM\Id()
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @var int
   *
   * @ORM\Column(type="integer")
   * @Assert\Choice(callback="getAvailableYears")
   */
  private $year;

  /**
   * @var Region
   *
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Geo\Region")
   * @Assert\NotNull()
   */
  private $region;

  /**
   * @var MedicalOrganization
   *
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Organization\MedicalOrganization")
   * @ORM\JoinColumn(referencedColumnName="code")
   *
   * @Assert\NotNull(groups={"step_2"})
   */
  private $medicalOrganization;

  /**
   * Плановое обращение(false) или неотложное(true)
   *
   * @var bool|null
   *
   * @ORM\Column(type="boolean", nullable=true)
   *
   * @Assert\NotNull(groups={"step_2"})
   */
  private $urgent;

  /**
   * @var Disease
   *
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Disease\Disease")
   */
  private $disease;

  /**
   * @var Patient
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User\Patient", cascade={"persist"})
   * @ORM\JoinColumn(nullable=true)
   */
  private $patient;

  /**
   * @var OmsChargeComplaintDocument[]|ArrayCollection
   * @ORM\OneToMany(targetEntity="AppBundle\Entity\OmsChargeComplaint\OmsChargeComplaintDocument", mappedBy="omsChargeComplaint", cascade={"persist"})
   */
  private $documents;

  /**
   * @var DateTime
   *
   * @ORM\Column(type="datetime", nullable=true)
   */
  private $paidAt;

  /**
   * @var int
   *
   * @ORM\Column(type="integer")
   */
  private $status = self::STATUS_DRAFT;

  /**
   * @var int
   *
   * @ORM\Column(type="integer")
   */
  private $draftStep = 1;

  /**
   * @var ?DateTime
   *
   * @ORM\Column(type="datetime", nullable=true)
   * @Gedmo\Timestampable(on="create")
   */
  private $createdAt;

  /**
   * @var ?DateTime
   *
   * @ORM\Column(type="datetime", nullable=true)
   * @Gedmo\Timestampable(on="update")
   */
  private $updatedAt;

  /**
   * @var ?DateTime
   *
   * @ORM\Column(type="datetime", nullable=true)
   */
  private $sentAt;

  /**
   * @var OmsChargeComplaintPatient
   * @ORM\OneToOne(targetEntity="AppBundle\Entity\OmsChargeComplaint\OmsChargeComplaintPatient", mappedBy="omsChargeComplaint", cascade={"persist"})
   */
  private $patientData;

  public function __construct ()
  {
    $this->documents = new ArrayCollection();
    $this->patientData = new OmsChargeComplaintPatient();
  }

  /**
   * @return int
   */
  public function getId(): int
  {
    return $this->id;
  }

  /**
   * @return int|null
   */
  public function getYear(): ?int
  {
    return $this->year;
  }

  /**
   * @param int $year
   * @return OmsChargeComplaint
   */
  public function setYear(int $year): OmsChargeComplaint
  {
    $this->year = $year;
    return $this;
  }

  /**
   * @return Region|null
   */
  public function getRegion(): ?Region
  {
    return $this->region;
  }

  /**
   * @param Region $region
   * @return OmsChargeComplaint
   */
  public function setRegion(Region $region): OmsChargeComplaint
  {
    $this->region = $region;
    return $this;
  }

  /**
   * @return MedicalOrganization|null
   */
  public function getMedicalOrganization(): ?MedicalOrganization
  {
    return $this->medicalOrganization;
  }

  /**
   * @param MedicalOrganization $medicalOrganization
   * @return OmsChargeComplaint
   */
  public function setMedicalOrganization(MedicalOrganization $medicalOrganization): OmsChargeComplaint
  {
    $this->medicalOrganization = $medicalOrganization;
    return $this;
  }

  /**
   * @return bool
   */
  public function isUrgent(): ?bool
  {
    return $this->urgent;
  }

  /**
   * @param bool $urgent
   * @return OmsChargeComplaint
   */
  public function setUrgent(bool $urgent): OmsChargeComplaint
  {
    $this->urgent = $urgent;
    return $this;
  }

  /**
   * @return Disease|null
   */
  public function getDisease(): ?Disease
  {
    return $this->disease;
  }

  /**
   * @param Disease $disease
   * @return OmsChargeComplaint
   */
  public function setDisease(Disease $disease): OmsChargeComplaint
  {
    $this->disease = $disease;
    return $this;
  }

  /**
   * @return Patient|null
   */
  public function getPatient(): ?Patient
  {
    return $this->patient;
  }

  /**
   * @param Patient $patient
   * @return OmsChargeComplaint
   */
  public function setPatient(Patient $patient): OmsChargeComplaint
  {
    $this->patient = $patient;
    return $this;
  }

  /**
   * @return OmsChargeComplaintDocument[]|ArrayCollection
   */
  public function getDocuments ()
  {
    return $this->documents;
  }

  /**
   * @param OmsChargeComplaintDocument[]|ArrayCollection $documents
   * @return $this
   */
  public function setDocuments ($documents)
  {
    $this->documents = new ArrayCollection();

    foreach ($documents as $document)
    {
      $this->addDocument($document);
    }

    return $this;
  }

  /**
   * @param OmsChargeComplaintDocument $document
   * @return $this
   */
  public function addDocument (OmsChargeComplaintDocument $document)
  {
    $this->documents->add($document);
    $document->setOmsChargeComplaint($this);
    return $this;
  }

  /**
   * @param OmsChargeComplaintDocument $document
   * @return $this
   */
  public function removeDocument (OmsChargeComplaintDocument $document)
  {
    $this->documents->removeElement($document);
    return $this;
  }

  /**
   * @return DateTime
   */
  public function getPaidAt(): ?DateTime
  {
    return $this->paidAt;
  }

  /**
   * @param DateTime $paidAt
   * @return OmsChargeComplaint
   */
  public function setPaidAt(DateTime $paidAt): OmsChargeComplaint
  {
    $this->paidAt = $paidAt;
    return $this;
  }

  /**
   * @return int
   */
  public function getStatus(): int
  {
    return $this->status;
  }

  /**
   * @param int $status
   * @return OmsChargeComplaint
   */
  public function setStatus(int $status): OmsChargeComplaint
  {
    if (!in_array($status, [
      self::STATUS_DRAFT,
      self::STATUS_CREATED,
      self::STATUS_SENT
    ]))
    {
      throw new \InvalidArgumentException(sprintf('OmsChargeComplaint invalid status %d', $status));
    }

    $this->status = $status;

    return $this;
  }

  /**
   * @return int
   */
  public function getDraftStep(): int
  {
    return $this->draftStep;
  }

  /**
   * @param int $draftStep
   * @return OmsChargeComplaint
   */
  public function setDraftStep(int $draftStep): OmsChargeComplaint
  {
    $this->draftStep = $draftStep;
    return $this;
  }

  /**
   * @return DateTime|null
   */
  public function getCreatedAt(): ?DateTime
  {
    return $this->createdAt;
  }

  /**
   * @param DateTime|null $createdAt
   * @return OmsChargeComplaint
   */
  public function setCreatedAt(?DateTime $createdAt): OmsChargeComplaint
  {
    $this->createdAt = $createdAt;
    return $this;
  }

  /**
   * @return DateTime|null
   */
  public function getUpdatedAt(): ?DateTime
  {
    return $this->updatedAt;
  }

  /**
   * @param DateTime|null $updatedAt
   * @return OmsChargeComplaint
   */
  public function setUpdatedAt(?DateTime $updatedAt): OmsChargeComplaint
  {
    $this->updatedAt = $updatedAt;
    return $this;
  }

  /**
   * @return DateTime|null
   */
  public function getSentAt(): ?DateTime
  {
    return $this->sentAt;
  }

  /**
   * @param DateTime|null $sentAt
   * @return OmsChargeComplaint
   */
  public function setSentAt(?DateTime $sentAt): OmsChargeComplaint
  {
    $this->sentAt = $sentAt;
    return $this;
  }

  /**
   * @return array
   */
  public function getAvailableYears()
  {
    return Year::getYears();
  }

  /**
   * @return OmsChargeComplaintPatient
   */
  public function getPatientData ()
  {
    if ($this->patientData === null)
    {
      $this->patientData = new OmsChargeComplaintPatient();
      $this->patientData->setOmsChargeComplaint($this);
    }

    return $this->patientData;
  }

  /**
   * @param OmsChargeComplaintPatient $patientData
   * @return $this
   */
  public function setPatientData ($patientData)
  {
    $this->patientData = $patientData;
    return $this;
  }
}
