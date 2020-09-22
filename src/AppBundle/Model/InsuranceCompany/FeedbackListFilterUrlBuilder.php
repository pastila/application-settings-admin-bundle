<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Model\InsuranceCompany;


use Symfony\Component\Routing\Router;

class FeedbackListFilterUrlBuilder
{
  private $originalFilter;

  private $router;

  public function __construct(FeedbackListFilter $originalFilter, Router $router)
  {
    $this->originalFilter = $originalFilter;
    $this->router = $router;
  }

  public function buildHttpQuery($values=[])
  {
    return http_build_query($this->getRouteParams($values));
  }

  public function buildUrl($values=[])
  {
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
    return $this->router->generate('app_insurancecompany_feedback_index');
  }
}
