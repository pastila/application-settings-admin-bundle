<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Model\InsuranceCompany;


use AppBundle\Form\InsuranceCompany\FeedbackListFilterType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\Router;

class FeedbackListFilterUrlBuilder
{
  private $originalFilter;

  private $routeName;

  private $router;

  private $formFactory;

  public function __construct(FeedbackListFilter $originalFilter, Router $router, FormFactoryInterface $formFactory,
    $routeName='app_insurancecompany_feedback_index')
  {
    $this->originalFilter = $originalFilter;
    $this->router = $router;
    $this->formFactory = $formFactory;
    $this->routeName = $routeName;
  }

  public function buildHttpQuery($values=[])
  {
    return http_build_query($this->getRouteParams($values));
  }

  public function buildUrl($values=[])
  {
    $filter = $this->originalFilter;

    /*
     * Если мы хотим собрать ссылку с еще каким-то параметром фильтра, кроме тех, что мы уже отвалидировали,
     * то нужно заново создать экземпляр формы фильтра и заполнить модель фильтра новыми значениями.
     */
    if (!empty($values))
    {
      //Главное - не уйти в бесконечную рекурсию
      $form = $this->formFactory->create(FeedbackListFilterType::class, new FeedbackListFilter(), [
        'url_builder' => $this
      ]);

      $form->submit($this->getFilterParams(isset($values['filter']) ? $values['filter'] : []));
      if ($form->isValid())
      {
        $filter = $form->getData();
      }
    }

    if ($filter->getCompany())
    {
      $routeParams = array_merge($this->getRouteParams($values), [
        'slug' => $filter->getCompany()->getSlug()
      ]);
      unset($routeParams['filter']['company']);

      return $this->router->generate('company_review_list', $routeParams);
    }

    return $this->router->generate('app_insurancecompany_feedback_index', $this->getRouteParams($values));
  }

  public function getRouteParams($values=[])
  {
    $params = [
      'filter' => $this->getFilterParams(isset($values['filter']) ? $values['filter'] : []),
      'page' => isset($values['page']) ? $values['page'] : 1
    ];

    if (empty($params['filter']))
    {
      unset($params['filter']);
    }
    if ($params['page'] <= 1)
    {
      unset($params['page']);
    }

    return $params;
  }

  public function getFilterParams($values=[])
  {
    return array_filter(array_merge($this->originalFilter->getValues(), $values), function($value) {
      return !empty($value) || 0 === $value;
    });
  }

  public function getBaseUrl()
  {
    return $this->router->generate($this->routeName);
  }
}
