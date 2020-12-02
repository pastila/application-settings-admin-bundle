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
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends Controller
{
  public function showAction(Request $request, $slug)
  {
    $article = $this->getDoctrine()
      ->getRepository('AppBundle:Common\Article')
      ->findOneBy(array('slug' => $slug));

    if (!$article)
    {
      throw $this->createNotFoundException(sprintf('Статья «%s» не найдена', $slug));
    }

    if (!$article->isPublished())
    {
      throw $this->createNotFoundException(sprintf('Статья «%s» не опубликована', $slug));
    }

    return $this->render('Article/show.html.twig', array(
      'article' => $article
    ));
  }
}