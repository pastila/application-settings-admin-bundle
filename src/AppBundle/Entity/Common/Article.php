<?php


namespace AppBundle\Entity\Common;

use Accurateweb\MediaBundle\Exception\OperationNotSupportedException;
use Accurateweb\NewsBundle\Model\NewsInterface;
use Accurateweb\MediaBundle\Model\Image\ImageAwareInterface;
use Accurateweb\MediaBundle\Model\Media\ImageInterface;
use AppBundle\Media\Article\ArticleImage;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Accurateweb\NewsBundle\Model\News as Base;

/**
 * Class Article
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Common\NewsRepository")
 * @ORM\Table(name="s_articles")
 */
class Article extends Base implements ImageAwareInterface
{
  /**
   * @var NewsInterface[]|ArrayCollection
   * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Common\Article")
   */
  protected $relatedNews;

  /**
   * @param null $id
   * @return ImageInterface|\Accurateweb\NewsBundle\Media\NewsImage|ArticleImage
   */
  public function getImage($id = null)
  {
    return new ArticleImage('article', $this->teaser);
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

  /**
   * @return ImageInterface
   */
  public function getTeaserImage ()
  {
    return $this->getImage();
  }

  /**
   * @param $image
   * @return $this|mixed
   */
  public function setTeaserImage ($image)
  {
    return $this->setImage($image);
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