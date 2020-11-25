<?php


namespace Accurateweb\NewsBundle\Service;

use Accurateweb\ClientApplicationBundle\DataAdapter\ClientApplicationModelAdapterInterface;
use Accurateweb\NewsBundle\Exception\PageNotFoundException;
use Accurateweb\NewsBundle\Model\NewsInterface;
use Accurateweb\NewsBundle\Model\Paginator\NewsPaginator;
use Accurateweb\NewsBundle\Repository\NewsRepositoryInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;

class NewsService
{
  const HTML_FORMAT = 'html';
  const JSON_FORMAT = 'json';

  private $repository, $twig, $newsDataAdapter;

  /**
   * NewsService constructor.
   *
   * @param NewsRepositoryInterface|EntityRepository $manager
   * @param Environment $twig
   * @param ClientApplicationModelAdapterInterface $newsDataAdapter
   */
  public function __construct(NewsRepositoryInterface $manager, Environment $twig,
                              ClientApplicationModelAdapterInterface $newsDataAdapter)
  {
    $this->repository = $manager;
    $this->twig = $twig;
    $this->newsDataAdapter = $newsDataAdapter;
  }

  /**
   * @param int $page
   * @param int $limit
   * @return NewsPaginator
   * @throws PageNotFoundException
   */
  public function createPaginator($page, $limit)
  {
    $qb = $this->repository->getNewsListQb();
    $qb->setMaxResults($limit);
    $qb->setFirstResult(($page - 1) * $limit);

    $paginator = new NewsPaginator($qb);
    $lastPage = $paginator->getMaxPage($limit);

    if (($lastPage < $page) && ($lastPage !== 0))
    {
      throw new PageNotFoundException("Page $page not found!");
    }

    return $paginator;
  }

  /**
   * @param NewsInterface[] $news
   * @param string $format
   * @return array|string
   * @throws \Twig_Error_Loader
   * @throws \Twig_Error_Runtime
   * @throws \Twig_Error_Syntax
   */
  public function adapteeNews($news, $format = self::HTML_FORMAT)
  {
    $result = null;
    if ($format === self::HTML_FORMAT)
    {
      foreach ($news as $item)
      {
        $result .= $this->transformItemToHtml($item);
      }
    }
    elseif ($format === self::JSON_FORMAT)
    {
      $result = [];

      foreach ($news as $item)
      {
        $result[] = $this->transformItemToArray($item);
      }
    }

    return $result;
  }

  /**
   * @param NewsInterface $item
   * @return string
   * @throws \Twig_Error_Loader
   * @throws \Twig_Error_Runtime
   * @throws \Twig_Error_Syntax
   */
  public function transformItemToHtml(NewsInterface $item)
  {
    return $this->twig->render('@AccuratewebGpnNews/item.html.twig', [
      'item' => $item
    ]);
  }

  /**
   * @param NewsInterface $item
   * @return array
   */
  public function transformItemToArray(NewsInterface $item)
  {
    return $this->newsDataAdapter->transform($item);
  }

  /**
   * @param int $slug
   * @return NewsInterface
   * @throws NotFoundHttpException
   */
  public function getOne($slug)
  {
    $news = $this->repository->findOneBy([
      'slug' => $slug
    ]);

    if (!$news || !$news->isPublished())
    {
      throw new NotFoundHttpException("News #$slug not found!");
    }

    return $news;
  }
}