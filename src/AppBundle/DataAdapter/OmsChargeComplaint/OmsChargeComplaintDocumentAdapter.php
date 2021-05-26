<?php

namespace AppBundle\DataAdapter\OmsChargeComplaint;

use Accurateweb\ClientApplicationBundle\DataAdapter\ClientApplicationModelAdapterInterface;
use AppBundle\Entity\OmsChargeComplaint\OmsChargeComplaintDocument;
use AppBundle\Model\File\FileStorage;

class OmsChargeComplaintDocumentAdapter implements ClientApplicationModelAdapterInterface
{
  private $fileStorage;

  public function __construct (FileStorage $fileStorage)
  {
    $this->fileStorage = $fileStorage;
  }

  /**
   * @param OmsChargeComplaintDocument $subject
   * @param array $options
   * @return array
   */
  public function transform ($subject, $options = array())
  {
    return [
      'originalFilename' => $subject->getOriginalFilename(),
      'fileSize' => $this->fileStorage->getFileSize($subject),
      'url' => $this->fileStorage->getUrl($subject),
      'extension' => $this->fileStorage->getExtension($subject),
    ];
  }

  public function getModelName ()
  {
    return 'AppealDocument';
  }

  public function supports ($subject)
  {
    return $subject instanceof OmsChargeComplaintDocument;
  }

  public function getName ()
  {
    return 'appeal_document';
  }

}