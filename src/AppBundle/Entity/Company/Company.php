<?php

namespace AppBundle\Entity\Company;

use Accurateweb\MediaBundle\Model\Image\ImageAwareInterface;
use Accurateweb\MediaBundle\Model\Media\ImageInterface;
use Accurateweb\MediaBundle\Model\Thumbnail\ImageThumbnail;
use AppBundle\Entity\Geo\Region;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Company.
 *
 * @ORM\Table(name="s_companies")
 * @ORM\Entity()
 */
class Company implements ImageAwareInterface, ImageInterface
{
  /**
   * @var integer
   * @ORM\Id()
   * @ORM\GeneratedValue(strategy="AUTO")
   * @ORM\Column(type="integer")
   */
  protected $id;

  /**
   * Название компании
   *
   * @var string
   *
   * @ORM\Column(name="name", type="string", length=512, nullable=false)
   */
  private $name;

  /**
   * Кпп компании
   *
   * @var string
   *
   * @ORM\Column(name="kpp", type="string", length=256, nullable=true)
   */
  private $kpp;

  /**
   * Логотип компании
   *
   * @var string
   *
   * @ORM\Column(name="image", type="string", length=255, nullable=true)
   */
  protected $file;

  /**
   * @var Feedback[]|ArrayCollection
   * @ORM\OneToMany(targetEntity="AppBundle\Entity\Company\Feedback", mappedBy="company")
   */
  protected $feedbacks;

  /**
   * @var Feedback[]|ArrayCollection
   * @ORM\OneToMany(targetEntity="AppBundle\Entity\Company\CompanyBranch", mappedBy="company")
   */
  protected $branches;

  /**
   * Company constructor.
   */
  public function __construct ()
  {
    $this->feedbacks = new ArrayCollection();
    $this->branches = new ArrayCollection();
  }

  /**
   * @return integer
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * @param string $name
   *
   * @return string
   */
  public function setName($name)
  {
    $this->name = $name;

    return $this;
  }

  /**
   * @param string $kpp
   *
   * @return string
   */
  public function setKpp($kpp)
  {
    $this->kpp = $kpp;

    return $this;
  }

  /**
   * @return string
   */
  public function getKpp ()
  {
    return $this->kpp;
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
    if ($file === null)
    {
      return $this;
    }

    $this->file = $file;

    return $this;
  }

  /**
   * @param null $id
   * @return ImageInterface
   */
  public function getImage ($id = null)
  {
    return $this;
  }

  /**
   * @param ImageInterface $image
   * @return $this
   */
  public function setImage (ImageInterface $image)
  {
    $this->setResourceId($image->getResourceId());

    return $this;
  }

  /**
   * @param $id
   * @return mixed|null
   */
  public function getImageOptions ($id)
  {
    return null;
  }

  /**
   * @param $id
   * @return $this
   */
  public function setImageOptions ($id)
  {
    return $this;
  }

  /**
   * @return array
   */
  public function getThumbnailDefinitions ()
  {
    return array();
  }

  /**
   * @param string $id
   * @return ImageThumbnail
   * @throws \Exception
   */
  public function getThumbnail ($id)
  {
    $definitions = $this->getThumbnailDefinitions();

    $found = false;

    foreach ($definitions as $definition)
    {
      if ($definition->getId() == $id)
      {
        $found = true;
        break;
      }
    }

    if (!$found)
    {
      throw new \Exception('Image thumbnail definition not found');
    }

    return new ImageThumbnail($id, $this);
  }

  /**
   * @return string
   */
  public function getResourceId ()
  {
    return $this->getFile();
  }

  /**
   * @param $id
   */
  public function setResourceId ($id)
  {
    $this->setFile($id);
  }

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->id ? $this->name : '';
  }
}
