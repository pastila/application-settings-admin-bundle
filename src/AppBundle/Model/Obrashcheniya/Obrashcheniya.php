<?php


namespace AppBundle\Model\Obrashcheniya;

/**
 * Class Obrashcheniya
 * @package AppBundle\Model\Obrashcheniya
 */
class Obrashcheniya
{
  private $region;

  private $year;

  /**
   * @return mixed
   */
  public function getRegion()
  {
    return $this->region;
  }

  /**
   * @param mixed $region
   */
  public function setRegion($region): void
  {
    $this->region = $region;
  }

  /**
   * @return mixed
   */
  public function getYear()
  {
    return $this->year;
  }

  /**
   * @param mixed $year
   */
  public function setYear($year): void
  {
    $this->year = $year;
  }
}