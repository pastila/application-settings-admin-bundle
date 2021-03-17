<?php

namespace AppBundle\Controller\Admin\Feedback;

use AppBundle\Entity\Company\InsuranceCompany;
use AppBundle\Entity\Geo\Region;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class FeedbackAdminController
 * @package AppBundle\Controller\Admin\Feedback
 */
class FeedbackAdminController extends CRUDController
{
  /**
   * @param Request $request
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function reloadRegionsAction(Request $request)
  {
    $company = $this->getDoctrine()->getRepository(InsuranceCompany::class)->find($request->request->get('id'));
    if (!$company)
    {
      throw new NotFoundHttpException();
    }

    $regions = $this->getDoctrine()
      ->getRepository(Region::class)
      ->getRegionsInCompanyQueryBuilder($company)
      ->orderBy('r.name', 'ASC')
      ->getQuery()
      ->getResult();

    return $this->render("@App/admin/feedback/regions.html.twig", array("regions" => $regions));
  }
}