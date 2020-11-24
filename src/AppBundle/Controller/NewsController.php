<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Common\News;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends Controller
{
  /**
   * @Route("/news", name="news_list")
   */
  public function indexAction(Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    $news = $em->getRepository(News::class)
      ->findNewsOrderByPublishedAt();

    return $this->render('@App/news_list.html.twig', [
      'news' => $news
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
