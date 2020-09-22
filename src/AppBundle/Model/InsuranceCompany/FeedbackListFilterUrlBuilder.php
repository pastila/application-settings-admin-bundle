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
    return http_build_query(['filter' => $this->getRouteParams($values)]);
  }

  public function getRouteParams($values=[])
  {
    return array_merge($this->originalFilter->getValues(), $values);
  }

  public function getBaseUrl()
  {
    return $this->router->generate('app_insurancecompany_feedback_index');
  }
}
