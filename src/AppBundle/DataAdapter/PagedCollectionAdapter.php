<?php

namespace AppBundle\DataAdapter;

use Accurateweb\ClientApplicationBundle\DataAdapter\ClientApplicationModelAdapterInterface;
use Accurateweb\ClientApplicationBundle\DataAdapter\ClientApplicationModelAdapterManagerInterface;
use AppBundle\Model\Pagination;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PagedCollectionAdapter implements ClientApplicationModelAdapterInterface
{
  protected $adapterManager;

  public function __construct (ClientApplicationModelAdapterManagerInterface $adapterManager)
  {
    $this->adapterManager = $adapterManager;
  }

  /**
   * @param Pagination $subject
   * @param array $options
   * @return array
   */
  public function transform ($subject, $options = array())
  {
    $resolver = new OptionsResolver();
    $resolver->setRequired('adapter');
    $resolver->setDefault('collection_name', 'items');
    $resolver->setDefault('inner_options', []);
    $resolver->setDefault('pager_per_page', null);
    $resolver->setAllowedTypes('adapter', ['Accurateweb\ClientApplicationBundle\DataAdapter\ClientApplicationModelAdapterInterface', 'string']);
    $options = $resolver->resolve($options);
    $collectionAdapter = $options['adapter'];
    $collectionName = $options['collection_name'];

    if (!$collectionAdapter instanceof ClientApplicationModelAdapterInterface)
    {
      $collectionAdapter = $this->adapterManager->getModelAdapter($collectionAdapter);
    }

    $collection = [];

    foreach ($subject->getIterator() as $item)
    {
      $collection[] = $collectionAdapter->transform($item, $options['inner_options']);
    }

    $count = $subject->count();
    $perPage = $subject->getMaxPerPage();

    if ($options['pager_per_page'])
    {
      $perPage = $options['pager_per_page'];
    }

    $data = [
      'pager' => [
        'page' => $subject->getPage(),
        'maxPerPage' => $perPage,
        'firstPage' => 1,
        'lastPage' => $subject->getLastPage(),
        'nbResults' => $count,
        'more' => min($subject->getMore(), $perPage),
      ],
      $collectionName => $collection,
    ];

    return $data;
  }

  public function getModelName ()
  {
    return 'Pages';
  }

  public function supports ($subject)
  {
    return $subject instanceof Pagination;
  }

  public function getName ()
  {
    return 'pagination';
  }
}