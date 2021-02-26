<?php

namespace AppBundle\Controller\Admin\Disease;

use AppBundle\Entity\Disease\DiseaseCategory;
use Doctrine\ORM\EntityManager;
use RedCode\TreeBundle\Controller\TreeAdminController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class CategoryAdminController
 * @package AppBundle\Controller\Admin\Disease
 */
class CategoryAdminController extends TreeAdminController
{
  public function reorderAllAction (Request $request)
  {
    $rep = $this->getDoctrine()->getRepository('AppBundle:Disease\DiseaseCategory');
    $rep->reorderAll();
    $this->addFlash('success', 'Дерево категорий было восстановлено');

    return $this->redirectToList();
  }

  /**
   * @param Request $request
   * @param DiseaseCategory $object
   * @return \Symfony\Component\HttpFoundation\Response|void|null
   */
  protected function preDelete (Request $request, $object)
  {
    $root = $object->getTreeRoot();

    if ($root->getId() == $object->getId())
    {
      $this->addFlash('error', 'Вы не можете удалить корневую категорию');
      return $this->redirectToList();
    }

    parent::preDelete($request, $object);
  }

  public function listAction ()
  {
    $request = $this->getRequest();

    if ($listMode = $request->get('_list_mode'))
    {
      $this->admin->setListMode($listMode);
    }

    $listMode = $this->admin->getListMode();

    if ($listMode === 'tree')
    {
      $this->admin->checkAccess('list');

      $preResponse = $this->preList($request);

      if ($preResponse !== null)
      {
        return $preResponse;
      }

      return $this->renderWithExtraParams(
        '@App/admin/category/tree.html.twig',
        [
          'action' => 'list',
          'csrf_token' => $this->getCsrfToken('sonata.batch'),
          '_sonata_admin' => $request->get('_sonata_admin'),
        ]
      );
    }

    return $this->redirect($this->admin->generateUrl('list', [
      '_list_mode' => 'tree',
    ]));
  }

  public function moveAction (Request $request)
  {
    $id = $request->get('id');
    $pos = $request->get('position');
    $parent = $request->get('parent');

    /** @var DiseaseCategory $category */
    $category = $this->getDoctrine()->getRepository('AppBundle:Disease\DiseaseCategory')->find($id);
    $parent = $this->getDoctrine()->getRepository('AppBundle:Disease\DiseaseCategory')->find($parent);

    if (!$category || !$parent)
    {
      throw new NotFoundHttpException();
    }

    if (!is_numeric($pos))
    {
      $pos = null;
    }

    $category->setParent($parent);
    $category->setPosition($pos);
    $this->getDoctrine()->getManager()->persist($category);
    $this->getDoctrine()->getManager()->flush();
    return new Response();
  }

  public function treeAction(Request $request)
  {
    $request = $this->getRequest();
    /** @var EntityManager $em */
    $em = $this->get('doctrine.orm.entity_manager');

    $operation = $request->get('operation');
    switch ($operation)
    {
      case 'get_node':
        $nodeId = $request->get('id');
        if ($nodeId)
        {
          $parentNode = $em->getRepository($this->admin->getClass())->find($nodeId);
          $nodes = $em->getRepository($this->admin->getClass())->getChildren($parentNode, true, 'position');
        }
        else
        {
          $nodes = $em->getRepository($this->admin->getClass())->getRootNodes();
        }

        $nodes = array_map(
          function($node)
          {
            return [
              'id' => $node->getId(),
              'text' => (string)$node,
              'children' => true,
            ];
          },
          $nodes
        );

        return new JsonResponse($nodes);
      case 'rename_node':
        $nodeId = $request->get('id');
        $nodeText = $request->get('text');
        $node = $em->getRepository($this->admin->getClass())->find($nodeId);

        $node->{'set' . ucfirst($this->admin->getTreeTextField())}($nodeText);
        $this->admin->getModelManager()->update($node);

        return new JsonResponse([
          'id' => $node->getId(),
          'text' => $node->{'get' . ucfirst($this->admin->getTreeTextField())}()
        ]);
      case 'create_node':
        $parentNodeId = $request->get('parent_id');
        $parentNode = $em->getRepository($this->admin->getClass())->find($parentNodeId);
        $nodeText = $request->get('text');
        $node = $this->admin->getNewInstance();
        $node->{'set' . ucfirst($this->admin->getTreeTextField())}($nodeText);
        $node->setParent($parentNode);
        $this->admin->getModelManager()->create($node);

        return new JsonResponse([
          'id' => $node->getId(),
          'text' => $node->{'get' . ucfirst($this->admin->getTreeTextField())}()
        ]);
      case 'delete_node':
        $nodeId = $request->get('id');
        $node = $em->getRepository($this->admin->getClass())->find($nodeId);
        $this->admin->getModelManager()->delete($node);

        return new JsonResponse();
    }

    throw new BadRequestHttpException('Unknown action for tree');
  }
}