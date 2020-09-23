<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Model\InsuranceCompany;


class FeedbackListFilter
{
  private $company;

  private $rating;

  private $region;

  private $page;

  private $moderation;

  /**
   * @return mixed
   */
  public function getCompany()
  {
    return $this->company;
  }

  /**
   * @param mixed $company
   * @return FeedbackListFilter
   */
  public function setCompany($company, $force=false)
  {
    if (null !== $company || $force)
    {
      $this->company = $company;
    }

    return $this;
  }

  /**
   * @return mixed
   */
  public function getRating()
  {
    return $this->rating;
  }

  /**
   * @param mixed $rating
   * @return FeedbackListFilter
   */
  public function setRating($rating)
  {
    $this->rating = $rating;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getRegion()
  {
    return $this->region;
  }

  /**
   * @param mixed $region
   * @return FeedbackListFilter
   */
  public function setRegion($region)
  {
    $this->region = $region;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getModeration()
  {
    return $this->moderation;
  }

  /**
   * @param mixed $moderation
   * @return FeedbackListFilter
   */
  public function setModeration($moderation)
  {
    $this->moderation = $moderation;
    return $this;
  }


  /**
   * @return mixed
   */
  public function getPage()
  {
    return $this->page;
  }

  /**
   * @param mixed $page
   * @return FeedbackListFilter
   */
  public function setPage($page)
  {
    $this->page = $page;
    return $this;
  }



  public function getValues()
  {
    $values = [];
    if ($this->getCompany())
    {
      $values['company'] = $this->getCompany()->getId();
    }
    if ($this->getRating())
    {
      $values['rating'] = $this->getRating();
    }
    if ($this->getRegion())
    {
      $values['region'] = $this->getRegion()->getId();
    }
    if ($this->getModeration())
    {
      $values['moderation'] = 0;
    }

    return $values;
  }
}
