<?php


namespace Accurateweb\NewsBundle\DataAdapter;

use Accurateweb\ClientApplicationBundle\DataAdapter\ClientApplicationModelAdapterInterface;
use Accurateweb\NewsBundle\Model\NewsInterface;
use AppBundle\Sluggable\SluggableInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsDataAdapter implements ClientApplicationModelAdapterInterface
{
  /**
   * @param NewsInterface|SluggableInterface $subject
   * @param array $options
   * @return array
   */
  public function transform($subject, $options = array())
  {
    $options = $this->configureDefaultOptions($options);

    $related = $subject->getRelatedNews()->count() > 0 && $options['transform_related'] ? [] : null;

    if ($related !== null)
    {
      $relatedNews = $subject->getRelatedNews();
      foreach ($relatedNews as $news)
      {
        $related[] = $this->transform($news);
      }
    }

    return [
      'id' => $subject->getId(),
      'title' => $subject->getTitle(),
      'teaser' => sprintf('/uploads/%s', $subject->getTeaser()),
      'announce' => $subject->getAnnounce(),
      'text' => $subject->getText(),
      'external_url' => $subject->getExternalUrl(),
      'is_external' => $subject->isExternal(),
      'published' => $subject->isPublished(),
      'published_at' => $subject->getPublishedAt(),
      'related_news' => $related,
      'slug' => $subject->getSlug()
    ];
  }

  public function getModelName()
  {
    return 'aw.news';
  }

  public function supports($subject)
  {
    return $subject instanceof NewsInterface;
  }

  public function getName()
  {
    return 'News';
  }

  /**
   * @param $options
   * [
   *  'transform_related' => bool - применить адаптер ко связным новостям
   * ]
   * @return array
   */
  public function configureDefaultOptions($options)
  {
    $resolver = new OptionsResolver();
    $resolver->setDefault('transform_related', true);

    return $resolver->resolve($options);
  }
}