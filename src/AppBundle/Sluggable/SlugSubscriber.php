<?php
/**
 *  (c) 2019 ИП Рагозин Денис Николаевич. Все права защищены.
 *
 *  Настоящий файл является частью программного продукта, разработанного ИП Рагозиным Денисом Николаевичем
 *  (ОГРНИП 315668300000095, ИНН 660902635476).
 *
 *  Алгоритм и исходные коды программного кода программного продукта являются коммерческой тайной
 *  ИП Рагозина Денис Николаевича. Любое их использование без согласия ИП Рагозина Денис Николаевича рассматривается,
 *  как нарушение его авторских прав.
 *   Ответственность за нарушение авторских прав наступает в соответствии с действующим законодательством РФ.
 */

/**
 * Created by PhpStorm.
 * User: evgeny
 * Date: 28.07.17
 * Time: 13:13
 */

namespace AppBundle\Sluggable;

use Accurateweb\SlugifierBundle\Model\SlugifierInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\Common\EventSubscriber;
use AppBundle\Entity\Company\Company;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class SlugSubscriber implements EventSubscriber
{
  private $slugifier;
  private $validator;

  public function __construct(SlugifierInterface $slugifier, ValidatorInterface $validator)
  {
    $this->slugifier = $slugifier;
    $this->validator = $validator;
  }

  public function getSubscribedEvents()
  {
    return array(
      'prePersist',
      'preUpdate'
    );
  }

  public function prePersist(LifecycleEventArgs $args)
  {
    $this->updateSlug($args);
  }

  public function preUpdate(LifecycleEventArgs $args)
  {
    $this->updateSlug($args);
  }

  /**
   * @param LifecycleEventArgs $args
   */
  public function updateSlug(LifecycleEventArgs $args)
  {
    $object = $args->getObject();

    if ($object instanceof SluggableInterface && !$object->getSlug())
    {
      $this->setObjectSlug($object, (string)$object->getSlugSource());
    }
  }

  private function setObjectSlug(SluggableInterface $object, $name)
  {
    $slug = $this->slugifier->slugify($name);
    $object->setSlug($slug);
    $violations = $this->validator->validate($object);

    if ($object instanceof Company)
    {
      $object->setSlugRoot($slug);
    }

    if (count($violations))
    {
      /** @var ConstraintViolationInterface $violation */
      foreach ($violations as $violation)
      {
        if ($violation->getPropertyPath() === 'slug')
        {
          $i = 1;

          if (preg_match('/.*\-(\d+)$/', $name, $m))
          {
            $i = ((int)$m[1])+1;
            $name = preg_replace('/\-(\d+)$/', '', $name);
          }

          $this->setObjectSlug($object, sprintf('%s-%d', $name, $i));
          break;
        }
      }
    }
  }
}