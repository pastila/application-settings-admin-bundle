<?php

namespace Accurateweb\GpnNewsBundle\Controller;

use Accurateweb\GpnNewsBundle\Exception\PageNotFoundException;
use Accurateweb\GpnNewsBundle\Model\NewsInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NewsController extends Controller
{
  /**
   * Default news per page limit.
   *
   * @var int
   */
  protected $limit = 15;

  /**
   * Amount lead news on first page.
   * If $lead === 0, then list has not lead items.
   *
   * @var int
   */
  protected $lead = 1;

  /**
   * News list
   *
   * @param Request $request
   * @return RedirectResponse|Response
   */
  public function indexAction(Request $request)
  {
    $page = $this->getPageFromRequest($request);
    $limit = $this->getLimitFromRequest($request);

    $newsService = $this->get('aw.service.news');

    try
    {
      $paginator = $newsService->createPaginator($page, $limit + $this->lead);
    }
    catch (PageNotFoundException $notFoundException)
    {
      # @FIXME if page not existing - use redirect to first page or throw 404?
      return $this->redirectToRoute('news_index', ['page' => 1]);
    }

    $reminder = $paginator->getRemainder($limit, $page);

    return $this->render('@AccuratewebGpnNews/list.html.twig', [
      'news' => $paginator->getQuery()->getResult(),
      'lead_amount' => $this->lead,
      'showMore' => $reminder > 0,
      'remainder' => $reminder,
      'current_page' => $page
    ]);
  }

  /**
   * @param Request $request
   * @return JsonResponse|RedirectResponse
   * @throws \Twig_Error_Loader
   * @throws \Twig_Error_Runtime
   * @throws \Twig_Error_Syntax
   */
  public function loadMoreAction(Request $request)
  {
    $newsService = $this->get('aw.service.news');

    $page = $this->getPageFromRequest($request);
    $limit = $this->getLimitFromRequest($request);

    $format = $request->get('format', $newsService::HTML_FORMAT);

    try
    {
      $paginator = $newsService->createPaginator($page, $limit);
    }
    catch (PageNotFoundException $notFoundException)
    {
      return new JsonResponse([
        'error' => $notFoundException->getMessage()
      ], 404);
    }

    $transformedNews = $newsService->adapteeNews($paginator->getQuery()->getResult(), $format);

    return new JsonResponse([
      'news' => $transformedNews,
      'page' => $page,
      'max_page' => $paginator->getMaxPage($limit),
      'limit' => $limit,
      'remainder' => $paginator->getRemainder($limit, $page)
    ]);
  }

  /**
   * @param $slug
   * @param Request $request
   * @return Response|JsonResponse
   */
  public function showAction($slug, Request $request)
  {
    $service = $this->get('aw.service.news');
    $item = $service->getOne($slug);

    return $request->isXmlHttpRequest() ?
      new JsonResponse([
        'news' => $service->transformItemToArray($item)
      ]) :
      $this->render('@AccuratewebGpnNews/show.html.twig', [
        'news' => $item
      ]);
  }

  /**
   * Not working yet
   * @ Route(path="/news/to/{id}", name="news_to")
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function loadToAction(Request $request)
  {
    $result = [];
    $repo = $this->get('doctrine')->getRepository('AppBundle:Common\News');
    $showMoreButton = true;
    $newKey = null;

    if (!($lastId = (int) $request->get('id')))
    {
      $result = $repo->findNewsOrderByPublishedAt();
    }
    else
    {
      /** @var NewsInterface[] $news */
      $news = $repo->findNewsOrderByPublishedAt(null);

      foreach ($news as $key => $_news)
      {
        if ($_news->getId() === $lastId)
        {
          $newKey = $key;
          for ($i = 0;$i < $this->limit;$i++)
          {
            if (isset($news[$newKey + 1]))
            {
              $result[] = $news[$newKey + 1];
              $newKey++;
            }
            else
            {
              $showMoreButton = false;
              break;
            }
          }
          break;
        }
      }
    }

    if ($showMoreButton && null !== $newKey)
    {
      $showMoreButton = (boolean) isset($news[$newKey + 1]);
    }

    $html = '';
    array_filter($result, function ($_news) use (&$html)
    {
      $html .= $this->get('twig')->render('@App/_patrial/news_item_home.html.twig', [
        'item' => $_news
      ]);
    });

    return new JsonResponse([
      'html' => $html,
      'show_more_button' => $showMoreButton,
      'remainder' => 50
    ]);
  }

  /**
   * Override it for resolve current page
   *
   * @param Request $request
   * @return int
   */
  protected function getPageFromRequest(Request $request)
  {
    $page = (int) $request->get('page', 1);
    !($page <= 0) ?: $page = 1;

    return $page;
  }

  /**
   * Override it for resolve limit
   *
   * @param Request $request
   * @return int
   */
  protected function getLimitFromRequest(Request $request)
  {
    $limit = (int) $request->get('count', $this->limit);
    !($limit <= 0) ?: $limit = $this->limit;

    return $limit;
  }
}