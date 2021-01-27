<?php

namespace AppBundle\Entity\Company;

use Accurateweb\MediaBundle\Model\Image\ImageAwareInterface;
use Accurateweb\MediaBundle\Model\Media\ImageInterface;
use Accurateweb\MediaBundle\Model\Thumbnail\ImageThumbnail;
use AppBundle\Sluggable\SluggableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Company.
 *
 * @ORM\Table(name="s_companies", indexes={@ORM\Index(name="bitrix_id_idx", columns={"bitrix_id"})})
 * @UniqueEntity("slug")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Company\CompanyRepository")
 */
class Company implements ImageAwareInterface, ImageInterface, SluggableInterface
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
   * @ORM\Column(name="kpp", type="string", length=256, nullable=true, unique=true)
   */
  private $kpp;

  /**
   * Оценка
   *
   * @var float
   * @ORM\Column(name="valuation", type="decimal", precision=8, scale=3, nullable=true)
   */
  private $valuation;

  /**
   * Логотип компании
   *
   * @var string
   *
   * @ORM\Column(name="image", type="string", length=255, nullable=true)
   */
  protected $file;

  /**
   * @var int
   * @ORM\Column(type="integer", nullable=false)
   */
  private $status;

  /**
   * @var Feedback[]|ArrayCollection
   * @ORM\OneToMany(targetEntity="AppBundle\Entity\Company\Feedback", mappedBy="company")
   */
  protected $feedbacks;

  /**
   * @var CompanyBranch[]|ArrayCollection
   * @ORM\OneToMany(targetEntity="AppBundle\Entity\Company\CompanyBranch", mappedBy="company")
   */
  protected $branches;

  /**
   * Company constructor.
   */
  public function __construct()
  {
    $this->branches = new ArrayCollection();
  }

  /**
   * @return CompanyBranch[]|ArrayCollection
   */
  public function getBranches()
  {
    return $this->branches;
  }

  /**
   * @return int
   */
  public function getFeedbacksCount()
  {
    $count = 0;
    foreach ($this->branches as $branch)
    {
      $count += count($branch->getFeedbacks());
    }

    return $count;
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
   * @return Company
   */
  public function setBitrixId(?int $bitrixId): Company
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
  public function getKpp()
  {
    return $this->kpp;
  }

  /**
   * @return float
   */
  public function getValuation()
  {
    return $this->valuation;
  }

  /**
   * @param $valuation
   * @return $this
   */
  public function setValuation($valuation)
  {
    $this->valuation = $valuation;

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
    return CompanyStatus::getName($this->status);
  }

  /**
   * @param $status
   * @return $this
   */
  public function setStatus($status)
  {
    if (null !== $status && !in_array($status, CompanyStatus::getAvailableValues()))
    {
      throw new \InvalidArgumentException();
    }

    $this->status = $status;

    return $this;
  }

  /**
   * @return string
   */
  public function getFile()
  {
    return $this->file;
  }

  /**
   * @param string $file
   * @return $this
   */
  public function setFile($file)
  {
    if ($file === null) {
      return $this;
    }

    $this->file = $file;

    return $this;
  }

  /**
   * @param null $id
   * @return ImageInterface
   */
  public function getImage($id = null)
  {
    return $this;
  }

  /**
   * @param ImageInterface $image
   * @return $this
   */
  public function setImage(ImageInterface $image)
  {
    $this->setResourceId($image->getResourceId());

    return $this;
  }

  /**
   * @param $id
   * @return mixed|null
   */
  public function getImageOptions($id)
  {
    return null;
  }

  /**
   * @param $id
   * @return $this
   */
  public function setImageOptions($id)
  {
    return $this;
  }

  /**
   * @return array
   */
  public function getThumbnailDefinitions()
  {
    return array();
  }

  /**
   * @param string $id
   * @return ImageThumbnail
   * @throws \Exception
   */
  public function getThumbnail($id)
  {
    $definitions = $this->getThumbnailDefinitions();

    $found = false;

    foreach ($definitions as $definition) {
      if ($definition->getId() == $id) {
        $found = true;
        break;
      }
    }

    if (!$found) {
      throw new \Exception('Image thumbnail definition not found');
    }

    return new ImageThumbnail($id, $this);
  }

  /**
   * @return string
   */
  public function getResourceId()
  {
    return $this->getFile();
  }

  /**
   * @param $id
   */
  public function setResourceId($id)
  {
    $this->setFile($id);
  }

  public function getRating()
  {
    return $this->getValuation();
  }

  public function getLogo()
  {
    return 849;
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
