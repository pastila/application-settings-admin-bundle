<?php


namespace AppBundle\Entity\Obrashcheniya;

use AppBundle\Entity\User\User;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="s_obrashcheniya_files")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Obrashcheniya\ObrashcheniyaFileRepository")
 */
class ObrashcheniyaFile
{
  use TimestampableEntity;

  /**
   * @var integer
   * @ORM\Id()
   * @ORM\GeneratedValue(strategy="AUTO")
   * @ORM\Column(type="integer")
   */
  protected $id;

  /**
   * Пользователь, которому принадлежит файл
   *
   * @var null|User
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User\User")
   * @ORM\JoinColumn(name="user_id", nullable=true, onDelete="RESTRICT")
   */
  private $author;

  /**
   * Тип файла,
   * сгенер.обращение или прикреплен.файлы
   *
   * @var string
   *
   * @ORM\Column(name="type", type="string", length=255, nullable=false)
   */
  private $type;

  /**
   * Файл
   *
   * @var string
   * @ORM\Column(name="file", type="string", length=512, nullable=false)
   */
  private $file;

  /**
   * ID из bitrix, номер обращения
   *
   * @var null|string
   * @ORM\Column(type="string", length=256, nullable=true)
   */
  protected $bitrixId;

  /**
   * @return integer
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @return null|User
   */
  public function getAuthor()
  {
    return $this->author;
  }

  /**
   * @param User $author
   *
   * @return $this
   */
  public function setAuthor($author)
  {
    $this->author = $author;

    return $this;
  }

  /**
   * @return int|string
   */
  public function getType()
  {
    return $this->type;
  }

  /**
   * @param $type
   * @return $this
   */
  public function setType($type)
  {
    if (null !== $type && !in_array($type, ObrashcheniyaFileType::getAvailableTypes()))
    {
      throw new \InvalidArgumentException();
    }
    $this->type = $type;

    return $this;
  }

  /**
   * @return int|string
   */
  public function getTypeLabel()
  {
    return ObrashcheniyaFileType::getName($this->type);
  }

  /**
   * @return string
   */
  public function getFile()
  {
    return $this->file;
  }

  /**
   * @param $file
   * @return $this
   */
  public function setFile($file)
  {
    $this->file = $file;

    return $this;
  }

  /**
   * @return string
   */
  public function getBitrixId()
  {
    return $this->bitrixId;
  }

  /**
   * @param string $bitrixId
   *
   * @return string
   */
  public function setBitrixId($bitrixId)
  {
    $this->bitrixId = $bitrixId;

    return $this;
  }

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->id ? $this->file : '';
  }
}