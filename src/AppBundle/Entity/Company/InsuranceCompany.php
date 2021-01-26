<?php

namespace AppBundle\Entity\Company;

use Accurateweb\MediaBundle\Exception\OperationNotSupportedException;
use Accurateweb\MediaBundle\Model\Image\ImageAwareInterface;
use Accurateweb\MediaBundle\Model\Media\ImageInterface;
use AppBundle\Model\Media\CompanyLogo;
use AppBundle\Sluggable\SluggableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Accurateweb\MediaBundle\Annotation as Media;

/**
 * Company.
 *
 * @ORM\Table(name="s_companies", indexes={@ORM\Index(name="bitrix_id_idx", columns={"bitrix_id"})})
 * @UniqueEntity("slug")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Company\CompanyRepository")
 */
class InsuranceCompany implements ImageAwareInterface, SluggableInterface
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
   * @ORM\Column(name="image", type="string", length=255, nullable=true)
   * @Media\Image(id="company_logos")
   */
  protected $logo;

  /**
   * @var boolean
   * @ORM\Column(type="boolean", nullable=false, options={"default"=true})
   */
  private $published;

  /**
   * @var InsuranceCompanyBranch[]|ArrayCollection
   * @ORM\OneToMany(targetEntity="InsuranceCompanyBranch", mappedBy="company")
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
   * @return InsuranceCompanyBranch[]|ArrayCollection
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
   * @return InsuranceCompany
   */
  public function setBitrixId(?int $bitrixId): InsuranceCompany
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
   * @return bool
   */
  public function getPublished()
  {
    return $this->published;
  }

  /**
   * @param $published
   * @return $this
   */
  public function setPublished($published)
  {
    $this->published = $published;

    return $this;
  }

  /**
   * @return string
   */
  public function getLogo()
  {
    return $this->logo;
  }

  /**
   * @param string $logo
   * @return $this
   */
  public function setLogo($logo)
  {
    if ($logo === null)
    {
      return $this;
    }

    $this->logo = $logo;

    return $this;
  }

  /**
   * @param null $id
   * @return ImageInterface
   */
  public function getImage($id = null)
  {
    return new CompanyLogo('company_logos', $this->logo);
  }

  /**
   * @param ImageInterface $image
   * @return $this
   */
  public function setImage(ImageInterface $image)
  {
    $this->setLogo($image->getResourceId());

    return $this;
  }

  /**
   * @param $image
   * @return $this
   */
  public function setLogoImage($image)
  {
    return $this->setImage($image);
  }

  /**
   * @return ImageInterface
   */
  public function getLogoImage()
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

  public function setImageOptions($id)
  {
    throw new OperationNotSupportedException();
  }


  public function getRating()
  {
    return $this->getValuation();
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
