<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Common\News;
use AppBundle\Model\Pagination;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends Controller
{
  /**
   * @Route("/news", name="news_list")
   */
  public function indexAction(Request $request)
  {
    $page = $request->query->get('page', 1);
    if ($page < 1)
    {
      throw $this->createNotFoundException(sprintf('Requested page %s not exist', $page));
    }

    $qb = $this->getDoctrine()->getRepository(News::class)
      ->getNewsListQb();

    $maxPerPage = 10;
    $pagination = new Pagination($qb, $page, $maxPerPage);
    $news = $pagination->getIterator();

    if ($pagination->getPageCount() < $page)
    {
      throw $this->createNotFoundException(sprintf('Requested page %s is out of range (1..%d)', $page, $pagination->getPageCount()));
    }

    return $this->render('News/list.html.twig', [
      'news' => $news,
      'pagination' => $pagination,
    ]);
  }

  /**
   * @Route(path="/news/{slug}", name="news_show")
   */
  public function showAction($slug)
  {
    $em = $this->getDoctrine()->getManager();

    /** @var News $news */
    $news = $em->getRepository(News::class)
      ->findOneBy(['slug' => $slug]);

    if (!$news || !$news->isPublished())
    {
      throw $this->createNotFoundException(sprintf('Новость «%s» не найдена. Возможно, она была удалена.', $slug));
    }

    return $this->render('News/show.html.twig', [
      'news' => $news
    ]);
  }
}
