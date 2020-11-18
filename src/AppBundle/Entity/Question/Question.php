<?php

namespace AppBundle\Entity\Question;

use Accurateweb\MediaBundle\Model\Image\ImageAwareInterface;
use Accurateweb\MediaBundle\Model\Media\ImageInterface;
use AppBundle\Model\Media\OriginalImage;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Accurateweb\MediaBundle\Annotation as Media;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Table(name="s_questions")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Question\QuestionRepository")
 */
class Question
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
   * @Gedmo\SortablePosition
   * @var integer
   * @ORM\Column(type="integer", nullable=false, options={"default"=0})
   */
  private $position = 0;

  /**
   * Заголовок
   *
   * @var string
   *
   * @ORM\Column(name="title", type="string", length=255, nullable=false)
   */
  private $question;

  /**
   * Описание
   *
   * @var string
   *
   * @ORM\Column(name="answer", type="text", nullable=false)
   */
  private $answer;

  /**
   * @return integer
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @return int
   */
  public function getPosition ()
  {
    return $this->position;
  }

  /**
   * @param int $position
   * @return $this
   */
  public function setPosition ($position)
  {
    $this->position = $position;
    return $this;
  }

  /**
   * @return string
   */
  public function getQuestion()
  {
    return $this->question;
  }

  /**
   * @param string $question
   *
   * @return string
   */
  public function setQuestion($question)
  {
    $this->question = $question;

    return $this;
  }

  /**
   * @return string
   */
  public function getAnswer()
  {
    return $this->answer;
  }

  /**
   * @param string $answer
   *
   * @return string
   */
  public function setAnswer($answer)
  {
    $this->answer = $answer;

    return $this;
  }

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->getId() ? $this->getQuestion() : 'Новый вопрос';
  }
}
