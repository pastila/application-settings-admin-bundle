<?php

namespace AppBundle\Entity\Menu;


use Accurateweb\ImagingBundle\Filter\CropFilterOptionsResolver;
use Accurateweb\ImagingBundle\Filter\FilterChain;
use Accurateweb\MediaBundle\Exception\OperationNotSupportedException;
use Accurateweb\MediaBundle\Model\Image\ImageAwareInterface;
use Accurateweb\MediaBundle\Model\Media\ImageInterface;
use Accurateweb\MediaBundle\Model\Thumbnail\ImageThumbnail;
use Accurateweb\MediaBundle\Model\Thumbnail\ThumbnailDefinition;
use AppBundle\Model\Media\MenuSocialImage;
use Doctrine\ORM\Mapping as ORM;
use Accurateweb\MediaBundle\Annotation as Media;

/**
 * @ORM\Table(name="s_menu_social")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Menu\MenuSocialRepository")
 */
class MenuSocial extends AbstractMenu implements ImageAwareInterface
{
  /**
   * @var string
   *
   * @ORM\Column(name="image", type="string", length=255, nullable=true)
   * @Media\Image(id="number")
   */
  protected $teaser;


  /**
   * @return string
   */
  public function getTeaser()
  {
    return $this->teaser;
  }

  /**
   * @param $teaser
   * @return $this
   */
  public function setTeaser($teaser)
  {
    if (!is_null($teaser))
    {
      $this->teaser = $teaser;
    }

    return $this;
  }

  /**
   * @return bool|null
   */
  public function getIsImageSvg()
  {
    if (!is_null($this->teaser))
    {
      $array = explode(".", $this->teaser);
      return (end($array) === 'svg') ? true : false;
    }
    return false;
  }

  /**
   * @param $id
   * @return MenuSocialImage
   */
  public function getImage($id = null)
  {
    return new MenuSocialImage('menu_social', $this->teaser);
  }

  /**
   * @param ImageInterface $image
   * @return $this|mixed
   */
  public function setImage(ImageInterface $image)
  {
    $this->setTeaser($image->getResourceId());
    return $this;
  }

  public function setTeaserImage ($image)
  {
    return $this->setImage($image);
  }

  public function getTeaserImage ()
  {
    return $this->getImage();
  }

  /**
   * @param $id
   * @return mixed
   */
  public function getImageOptions($id)
  {
    return null;
  }

  /**
   * @param $id
   * @throws OperationNotSupportedException
   */
  public function setImageOptions($id)
  {
    throw new OperationNotSupportedException();
  }
}
