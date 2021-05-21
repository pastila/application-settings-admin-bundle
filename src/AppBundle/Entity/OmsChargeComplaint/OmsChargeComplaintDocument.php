<?php

namespace AppBundle\Entity\OmsChargeComplaint;

use AppBundle\Model\File\FileAwareInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="s_oms_charge_complaint_documents")
 */
class OmsChargeComplaintDocument implements FileAwareInterface
{
  /**
   * @var integer
   * @ORM\Column(type="integer")
   * @ORM\Id()
   * @ORM\GeneratedValue()
   */
  private $id;

  /**
   * @var string
   * @ORM\Column(type="string", length=255, nullable=true)
   */
  private $file;

  /**
   * @var integer
   * @ORM\Column(type="integer", nullable=true)
   */
  private $fileSize;

  /**
   * @var string
   * @ORM\Column(type="string", length=10, nullable=true)
   */
  private $fileExtension;

  /**
   * @var string
   * @ORM\Column(type="string", length=255, nullable=true)
   */
  private $originalFilename;

  /**
   * @var OmsChargeComplaint
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\OmsChargeComplaint\OmsChargeComplaint", inversedBy="documents")
   * @ORM\JoinColumn(nullable=false)
   */
  private $omsChargeComplaint;

  /**
   * @return int
   */
  public function getId ()
  {
    return $this->id;
  }

  /**
   * @return string
   */
  public function getFile ()
  {
    return $this->file;
  }

  /**
   * @param string $file
   * @return $this
   */
  public function setFile ($file)
  {
    $this->file = $file;
    return $this;
  }

  /**
   * @return int
   */
  public function getFileSize ()
  {
    return $this->fileSize;
  }

  /**
   * @param int $fileSize
   * @return $this
   */
  public function setFileSize ($fileSize)
  {
    $this->fileSize = $fileSize;
    return $this;
  }

  /**
   * @return string
   */
  public function getFileExtension ()
  {
    return $this->fileExtension;
  }

  /**
   * @param string $fileExtension
   * @return $this
   */
  public function setFileExtension ($fileExtension)
  {
    $this->fileExtension = $fileExtension;
    return $this;
  }

  /**
   * @return string
   */
  public function getOriginalFilename ()
  {
    return $this->originalFilename;
  }

  /**
   * @param string $originalFilename
   * @return $this
   */
  public function setOriginalFilename ($originalFilename)
  {
    $this->originalFilename = $originalFilename;
    return $this;
  }

  /**
   * @return OmsChargeComplaint
   */
  public function getOmsChargeComplaint ()
  {
    return $this->omsChargeComplaint;
  }

  /**
   * @param OmsChargeComplaint $omsChargeComplaint
   * @return $this
   */
  public function setOmsChargeComplaint ($omsChargeComplaint)
  {
    $this->omsChargeComplaint = $omsChargeComplaint;
    return $this;
  }
}