<?php

namespace Tests\AppBundle\Unit\EventListener;

use AppBundle\Entity\Company\FeedbackModerationStatus;
use Tests\AppBundle\AppWebTestCase;
use Tests\AppBundle\Fixtures\Company\Feedback;

class FeedbackEventTest extends AppWebTestCase
{
  protected function setUpFixtures ()
  {
    parent::setUpFixtures();
    $this->addFixture(new Feedback());
  }

  /**
   * https://jira.accurateweb.ru/browse/BEZBAHIL-241
   * @throws \Doctrine\ORM\ORMException
   * @throws \Doctrine\ORM\OptimisticLockException
   */
  public function testFeedbackUpdateAt()
  {
    $em = $this->getEntityManager();

    /**
     * @var \AppBundle\Entity\Company\Feedback $feedback
     */
    $feedback = $this->getReference('feedback-moderation-not');
    $this->assertTrue(empty($feedback->getUpdatedAt()), 'По умолчанию дата изменения отзыва должна быть пустой');

    $feedback->setModerationStatus(FeedbackModerationStatus::MODERATION_ACCEPTED);
    $em->persist($feedback);
    $em->flush();
    $this->assertTrue(empty($feedback->getUpdatedAt()), 'Дата изменения не должна меняться при любых изменениях отзыва (напр. модерации).');

    $feedback->setTitle('Новый заголовк');
    $em->persist($feedback);
    $em->flush();
    $this->assertTrue(!empty($feedback->getUpdatedAt()), 'Заголовок изменен, Дата изменения отзыва должна быть установлена автоматически равной текущей дате в случае,
    если пользователь или администратор поменяли в отзыве значение хотя бы одного из следующих полей: заголовок, текст отзыва, оценка');

    $feedback->setTitle('Новый заголовк с указанием updatedAt');
    $feedback->setUpdatedAt(new \DateTime('2021-01-01'));
    $em->persist($feedback);
    $em->flush();
    $this->assertEquals(new \DateTime('2021-01-01'), $feedback->getUpdatedAt(), 'Если при условии п.п. 3 администратор одновременно с сохранением отзыва поменял и дату изменения, 
    то дата изменения отзыва должна быть установлена соответствующей той дате, которую указал администратор');

    $feedback->setTitle('Новый заголовк с пустым updatedAt');
    $feedback->setUpdatedAt(null);
    $em->persist($feedback);
    $em->flush();
    $this->assertEquals(null, $feedback->getUpdatedAt(), 'Если указали пустую дату, то текущая дата изменения должна быть сброшена.');

    $feedback->setText('Новый текст');
    $em->persist($feedback);
    $em->flush();
    $this->assertTrue(!empty($feedback->getUpdatedAt()), 'Текст изменен, Дата изменения отзыва должна быть установлена автоматически равной текущей дате в случае,
    если пользователь или администратор поменяли в отзыве значение хотя бы одного из следующих полей: заголовок, текст отзыва, оценка');

    $feedback->setUpdatedAt(null);
    $em->persist($feedback);
    $em->flush();
    $feedback->setValuation(1);
    $em->persist($feedback);
    $em->flush();
    $this->assertTrue(!empty($feedback->getUpdatedAt()), 'Оценка изменена, Дата изменения отзыва должна быть установлена автоматически равной текущей дате в случае,
    если пользователь или администратор поменяли в отзыве значение хотя бы одного из следующих полей: заголовок, текст отзыва, оценка');
  }
}