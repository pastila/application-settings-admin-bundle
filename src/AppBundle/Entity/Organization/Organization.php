<?php

namespace AppBundle\Entity\Organization;

use Accurateweb\MediaBundle\Model\Image\ImageAwareInterface;
use Accurateweb\MediaBundle\Model\Media\ImageInterface;
use Accurateweb\MediaBundle\Model\Thumbnail\ImageThumbnail;
use AppBundle\Sluggable\SluggableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Organization.
 *
 * @ORM\Table(name="s_organisations")
 * @UniqueEntity("slug")
 * @ORM\Entity()
 */
class Organization implements SluggableInterface
{
  /**
   * @var integer
   * @ORM\Id()
   * @ORM\GeneratedValue(strategy="AUTO")
   * @ORM\Column(type="integer")
   */
  protected $id;

  /**
   * @var integer|null
   *
   * @ORM\Column(type="integer", nullable=true)
   */
  private $bitrixId;


  /**
   * @var string
   *
   * @ORM\Column(length=255, nullable=true)
   */
  protected $slugRoot;

  /**
   * @var string
   *
   * @ORM\Column(length=255, unique=true)
   */
  private $slug;

  /**
   * Название МО
   *
   * @var string
   *
   * @ORM\Column(name="name", type="string", length=255, nullable=false)
   */
  private $name;

  /**
   * Полное название МО
   *
   * @var string
   *
   * @ORM\Column(name="name_full", type="string", length=512, nullable=true)
   */
  private $nameFull;

  /**
   * Код МО
   *
   * @var string
   *
   * @ORM\Column(name="code", type="string", length=255, nullable=true)
   */
  private $code;

  /**
   * Адрес МО
   *
   * @var string
   *
   * @ORM\Column(name="address", type="string", length=512, nullable=true)
   */
  private $address;

  /**
   * Фамилия
   *
   * @var string
   *
   * @ORM\Column(name="address", type="string", length=255, nullable=true)
   */
  private $lastName;

  /**
   * Имя
   *
   * @var string
   *
   * @ORM\Column(name="address", type="string", length=255, nullable=true)
   */
  private $firstName;

  /**
   * Отчество
   *
   * @var string
   *
   * @ORM\Column(name="address", type="string", length=255, nullable=true)
   */
  private $middleName;

  /**
   * @var int
   * @ORM\Column(type="integer", nullable=false)
   */
  private $status;

  /**
   * Company constructor.
   */
  public function __construct()
  {
  }

  /**
   * @return integer
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @return int|null
   */
  public function getBitrixId(): ?int
  {
    return $this->bitrixId;
  }

  /**
   * @param int|null $bitrixId
   * @return $this
   */
  public function setBitrixId(?int $bitrixId)
  {
    $this->bitrixId = $bitrixId;
    return $this;
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
   * @return int
   */
  public function getStatus()
  {
    return $this->status;
  }

  /**
   * @return int|string
   */
  public function getStatusLabel ()
  {
    return OrganizationStatus::getName($this->status);
  }

  /**
   * @param $status
   * @return $this
   */
  public function setStatus($status)
  {
    if (null !== $status && !in_array($status, OrganizationStatus::getAvailableValues()))
    {
      throw new \InvalidArgumentException();
    }

    $this->status = $status;

    return $this;
  }

  public function getSlugSource()
  {
    return $this->getName();
  }

  /**
   * @return string
   */
  public function getSlug()
  {
    return $this->slug;
  }

  /**
   * @param string $slug
   * @return $this
   */
  public function setSlug($slug)
  {
    $this->slug = $slug;

    return $this;
  }

  /**
   * @return string
   */
  public function getSlugRoot()
  {
    return $this->slugRoot;
  }

  /**
   * @param string $slugRoot
   * @return $this
   */
  public function setSlugRoot($slugRoot)
  {
    $this->slugRoot = $slugRoot;
    return $this;
  }

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->id ? $this->name : '';
  }
}
