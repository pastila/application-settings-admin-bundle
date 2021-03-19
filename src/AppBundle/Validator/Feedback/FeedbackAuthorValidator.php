<?php

namespace AppBundle\Validator\Feedback;

use AppBundle\Entity\Company\Feedback;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class FeedbackAuthorValidator
 * @package AppBundle\Validator\Feedback
 */
class FeedbackAuthorValidator extends ConstraintValidator
{
  /**
   * @param Feedback $value
   * @param Constraint $constraint
   */
  public function validate($value, Constraint $constraint)
  {
    if (!$constraint instanceof FeedbackAuthor) {
      throw new UnexpectedTypeException($constraint, FeedbackAuthor::class);
    }

    $object = $this->context->getObject();
    $form = $object->getParent();
    /**
     * @var Feedback $feedback
     */
    $feedback = $form->getData();
    if (!$feedback instanceof Feedback) {
      throw new UnexpectedTypeException($feedback, Feedback::class);
    }

    if (!$feedback->getAuthor() && empty($value))
    {
      $this->context->addViolation($constraint->message, [
        '{message}' => 'Если для отзыва не выбран "Автор", то должен быть заполнен "Псевдоним"!',
      ]);
    }
  }
}