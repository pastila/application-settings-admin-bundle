<?php

namespace AppBundle\Entity\Geo;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Table(name="s_regions")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Geo\RegionRepository")
 */
class Region
{
  /**
   * @var null|int
   * @ORM\Id()
   * @ORM\GeneratedValue(strategy="AUTO")
   * @ORM\Column(type="integer")
   * @JMS\Exclude()
   */
  protected $id;

  /**
   * Код страны.
   *
   * @var null|int
   * @ORM\Column(type="integer", nullable=false, unique=true)
   * @JMS\Type("integer")
   */
  protected $code;

  /**
   * Название страны.
   *
   * @var null|string
   * @ORM\Column(type="string", nullable=false)
   * @JMS\Type("string")
   * @JMS\SerializedName("name")
   */
  protected $name;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function setId(?int $id): Country
  {
    $this->id = $id;

    return $this;
  }

  public function getTitle(): ?string
  {
    return $this->title;
  }

  public function setTitle(?string $title): Country
  {
    $this->title = $title;

    return $this;
  }

  public function getCode(): ?int
  {
    return $this->code;
  }

  public function setCode(?int $code): Country
  {
    $this->code = $code;

    return $this;
  }

  public function getMaskPhone(): ?string
  {
    return $this->maskPhone;
  }

  public function setMaskPhone(?string $maskPhone): Country
  {
    $this->maskPhone = $maskPhone;

    return $this;
  }

  public function getMinCost(): ?int
  {
    return $this->minCost;
  }

  public function setMinCost(?int $minCost): Country
  {
    $this->minCost = $minCost;

    return $this;
  }

  public function getMaxCost(): ?int
  {
    return $this->maxCost;
  }

  public function setMaxCost(?int $maxCost): Country
  {
    $this->maxCost = $maxCost;

    return $this;
  }

  public function isShowService(): ?bool
  {
    return $this->showService;
  }

  public function setShowService(bool $showService): Country
  {
    $this->showService = $showService;

    return $this;
  }

  public function getPosition(): ?int
  {
    return $this->position;
  }

  public function setPosition(?int $position): Country
  {
    $this->position = $position;

    return $this;
  }

  public function __toString()
  {
    return $this->getId() ? $this->getTitle() : 'Новая страна';
  }
}
