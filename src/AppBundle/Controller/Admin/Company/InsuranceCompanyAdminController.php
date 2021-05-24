<?php

namespace AppBundle\Controller\Admin\Company;

use AppBundle\Entity\Company\InsuranceCompany;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InsuranceCompanyAdminController extends CRUDController
{
  public function branchesAction (Request $request)
  {
    $id = $request->get('id');

    if (!$id)
    {
      throw new BadRequestHttpException();
    }

    /** @var InsuranceCompany $company */
    $company = $this->getDoctrine()->getRepository('AppBundle:Company\InsuranceCompany')->find($id);

    if ($company === null)
    {
      throw new NotFoundHttpException();
    }

    $branches = $company->getBranches();
    $data = [];

    foreach ($branches as $branch)
    {
      if ($branch->isPublished())
      {
        $data[] = [
          'id' => $branch->getId(),
          'code' => $branch->getCode(),
          'region' => $branch->getRegionName(),
        ];
      }
    }

    return $this->json($data);
  }
}