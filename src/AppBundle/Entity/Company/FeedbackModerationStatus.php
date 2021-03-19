<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Entity\Company;


abstract class FeedbackModerationStatus
{
  const MODERATION_NONE = 0;
  const MODERATION_REJECTED = -1;
  const MODERATION_ACCEPTED = 1;

  /**
   * @return array
   */
  public static function getAvailableValues()
  {
    return [
      self::MODERATION_NONE,
      self::MODERATION_ACCEPTED,
      self::MODERATION_REJECTED
    ];
  }

  /**
   * @return array
   */
  public static function getAvailableNames()
  {
    return [
      FeedbackModerationStatus::MODERATION_ACCEPTED => 'Одобрено',
      FeedbackModerationStatus::MODERATION_REJECTED => 'Отклонено',
      FeedbackModerationStatus::MODERATION_NONE => 'Требует модерации',
    ];
  }
}
