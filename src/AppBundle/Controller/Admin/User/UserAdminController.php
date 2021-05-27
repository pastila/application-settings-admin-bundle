<?php

namespace AppBundle\Controller\Admin\User;

use AppBundle\Entity\User\User;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\HttpFoundation\Request;

class UserAdminController extends CRUDController
{
  /**
   * @param Request $request
   * @param User $user
   * @return \Symfony\Component\HttpFoundation\Response|null
   */
  protected function preDelete (Request $request, $user)
  {
    $nbFeedbacks = $this->getDoctrine()->getRepository('AppBundle:Company\Feedback')->countUserReviews($user);
    $nbPatients = $this->getDoctrine()->getRepository('AppBundle:User\Patient')->countByUser($user);

    if ($nbFeedbacks > 0)
    {
      $this->addFlash('error', 'У пользователя есть привязанные отзывы. Удаление невозможно.');
      return $this->redirectToList();
    }

    if ($nbPatients > 0)
    {
      $this->addFlash('error', 'У пользователя есть привязанные пациенты. Удаление невозможно.');
      return $this->redirectToList();
    }
  }

  public function batchActionDelete (ProxyQueryInterface $query)
  {
    $queryProxy = clone $query;
    $queryProxy->select('DISTINCT ' . current($queryProxy->getRootAliases()));

    foreach ($queryProxy->getQuery()->iterate() as $pos => $object)
    {
      /** @var User $user */
      $user = $object[0];
      $nbFeedbacks = $this->getDoctrine()->getRepository('AppBundle:Company\Feedback')->countUserReviews($user);
      $nbPatients = $this->getDoctrine()->getRepository('AppBundle:User\Patient')->countByUser($user);

      if ($nbFeedbacks > 0)
      {
        $this->addFlash('error', sprintf('У пользователя %s есть привязанные отзывы. Удаление невозможно.', $user->getEmail()));

        $query->andWhere(sprintf('o != :author_%s', $pos));
        $query->setParameter(sprintf('author_%s', $pos), $user);
      }
      elseif ($nbPatients > 0)
      {
        $this->addFlash('error', sprintf('У пользователя %s есть привязанные пациенты. Удаление невозможно.', $user->getEmail()));

        $query->andWhere(sprintf('o != :author_%s', $pos));
        $query->setParameter(sprintf('author_%s', $pos), $user);
      }
    }

    return parent::batchActionDelete($query);
  }
}