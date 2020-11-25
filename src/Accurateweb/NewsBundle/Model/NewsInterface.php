<?php


namespace Accurateweb\NewsBundle\Model;

use Accurateweb\MediaBundle\Model\Media\ImageInterface;
use AppBundle\Media\Text\NewsImage;
use Doctrine\Common\Collections\ArrayCollection;


interface NewsInterface
{
  /**
   * @return int
   */
  public function getId();

  /**
   * @param int $id
   */
  public function setId($id);

  /**
   * @return string
   */
  public function getTitle();

  /**
   * @param string $title
   */
  public function setTitle($title);

  /**
   * @return string
   */
  public function getAnnounce();

  /**
   * @param string $announce
   */
  public function setAnnounce($announce);

  /**
   * @return string
   */
  public function getText();

  /**
   * @param string $text
   */
  public function setText($text);

  /**
   * @return bool
   */
  public function isPublished();

  /**
   * @param bool $isPublished
   */
  public function setIsPublished($isPublished);

  /**
   * @return \DateTime
   */
  public function getPublishedAt();

  /**
   * @param \DateTime $publishedAt
   */
  public function setPublishedAt($publishedAt);

  /**
   * @return \DateTime
   */
  public function getCreatedAt();

  /**
   * @param \DateTime $createdAt
   */
  public function setCreatedAt($createdAt);

  /**
   * @param $id
   * @return mixed
   */
  public function getImageOptions($id);

  public function setImageOptions($id);

  /**
   * @return string
   */
  public function getTeaser();

  /**
   * @param string $teaser
   * @return $this
   */
  public function setTeaser($teaser);

  /**
   * @return array
   */
  public function getTeaserImageOptions();

  /**
   * @param array $teaserImageOptions
   * @return News
   */
  public function setTeaserImageOptions($teaserImageOptions);

  /**
   * @return NewsImage
   */
  public function getImage($id = null);

  /**
   * @param NewsImage $teaser
   */
  public function setImage(ImageInterface $teaser);


  /**
   * @return string
   */
  public function getExternalUrl();

  /**
   * @param string $externalUrl
   * @return $this
   */
  public function setExternalUrl($externalUrl);

  /**
   * @return bool
   */
  public function isExternal();

  /**
   * @param bool $isExternal
   * @return $this
   */
  public function setIsExternal($isExternal);

  /**
   * @return ArrayCollection|News[]
   */
  public function getRelatedNews();

  /**
   * @param ArrayCollection|News[] $relatedNews
   * @return $this
   */
  public function setRelatedNews($relatedNews);
}