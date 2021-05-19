<?php

namespace AppBundle\Model\Filter;

use AppBundle\Entity\Geo\Region;

class MedicalOrganizationFilter
{
  /**
   * @var string
   */
  private $query;

  /**
   * @var string
   */
  private $year;

  /**
   * @var Region
   */
  private $region;

  /**
   * @return string
   */
  public function getQuery ()
  {
    return $this->query;
  }

  /**
   * @param string $query
   * @return $this
   */
  public function setQuery ($query)
  {
    $this->query = $query;
    return $this;
  }

  /**
   * @return string
   */
  public function getYear ()
  {
    return $this->year;
  }

  /**
   * @param string $year
   * @return $this
   */
  public function setYear ($year)
  {
    $this->year = $year;
    return $this;
  }

  /**
   * @return Region
   */
  public function getRegion ()
  {
    return $this->region;
  }

  /**
   * @param Region $region
   * @return $this
   */
  public function setRegion ($region)
  {
    $this->region = $region;
    return $this;
  }
}