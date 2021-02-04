<?php

namespace AppBundle\Controller\Admin\Import;

use Accurateweb\TaskSchedulerBundle\Model\MetaData;
use Sonata\AdminBundle\Controller\CRUDController;
use AppBundle\Form\Admin\Organization\OrganizationImportType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class OrganizationAdminController extends CRUDController
{
  /**
   * @param Request $request
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function importAction(Request $request)
  {
    $form = $this->container->get('form.factory')->create(OrganizationImportType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid())
    {
      $data = $form->getData();
      /** @var UploadedFile $file */
      $file = $data['file'];
      $pathname = null;
      try
      {
        $pathname = $this->container->get('app.organization.uploader')->upload($file);
      } catch (FileException $e)
      {
        $this->container->get('logger')->addError(sprintf('An FileException occurred while uploading a file to import organizations: %s', $e->getMessage()));
      }

      if ($pathname)
      {
        $job = $this->container->get('app.organization_import.bg_job');
        $this->container->get('aw.task_scheduler.background_job_manager')->addToQueue($job, new MetaData(null, [
          'filename' => $pathname,
          'year' => $data['year'],
        ], [
          'email' => $this->getUser()->getEmail(),
        ]));
        $this->addFlash('success', 'Файл успешно загружен и добавлен в очередь на импорт');
        return $this->redirectToRoute('admin_app_organization_organization_list');
      } else
      {
        $this->addFlash('error', 'Во время загрузки файла произошла ошибка!');
      }
    }

    return $this->renderWithExtraParams('@App/admin/organization_import/import.html.twig', [
      'action' => 'import',
      'elements' => $this->admin->getShow(),
      'form' => $form->createView(),
    ], null);
  }
}