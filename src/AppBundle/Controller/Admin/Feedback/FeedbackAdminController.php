<?php

namespace AppBundle\Controller\Admin\Feedback;


use AppBundle\Entity\Company\CompanyBranch;
use AppBundle\Entity\Company\CompanyStatus;
use AppBundle\Entity\Geo\Region;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;

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
    $regions = $this->getDoctrine()
      ->getRepository(Region::class)
      ->createQueryBuilder('r')
      ->leftJoin('r.branches', 'cb')
      ->leftJoin('cb.company', 'c')
      ->where('cb.company = :company')
      ->andWhere('cb.status = :status')
      ->andWhere('c.status = :status')
      ->setParameter('company', $request->request->get('id'))
      ->setParameter('status', CompanyStatus::ACTIVE)
      ->orderBy('r.name')
      ->getQuery()
      ->getResult();

    return $this->render("@App/admin/feedback/regions.html.twig", array("regions" => $regions));
  }
}