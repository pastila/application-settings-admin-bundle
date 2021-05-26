<?php

namespace AppBundle\Controller\Admin\Organization;

use Accurateweb\TaskSchedulerBundle\Model\MetaData;
use AppBundle\Entity\Organization\MedicalOrganization;
use AppBundle\Form\Admin\Organization\OrganizationExportType;
use AppBundle\Form\Admin\Organization\OrganizationImportType;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MedicalOrganizationAdminController extends CRUDController
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
        return $this->redirectToList();
      } else
      {
        $this->addFlash('error', 'Во время загрузки файла произошла ошибка!');
      }
    }

    return $this->renderWithExtraParams('@App/admin/organization/import.html.twig', [
      'action' => 'import',
      'elements' => $this->admin->getShow(),
      'form' => $form->createView(),
    ], null);
  }

  /**
   * @param Request $request
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function exportAction(Request $request)
  {
    $form = $this->container->get('form.factory')->create(OrganizationExportType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid())
    {
      $data = $form->getData();
      $em = $this->container->get('doctrine.orm.entity_manager');
      $organizationsListQb = $em->getRepository(MedicalOrganization::class)
        ->createQueryBuilder('o');
      if (!empty($data['year']))
      {
        $organizationsListQb
          ->join('o.years', 'y')
          ->andWhere('y.year = :year')
          ->setParameter('year', $data['year']);
      }
      $printer = $this->container->get('app.organization_export.excel');
      $organizations = $organizationsListQb->getQuery()->getResult();

      $fileName = 'mo_' . date('Y-m-d') . '.xls';
      $response = new StreamedResponse(
        function() use ($printer, $organizations)
        {
          $printer->doPrint($organizations);
        },
        200,
        [
          'Content-Disposition' => sprintf('%s;filename="%s"', ResponseHeaderBag::DISPOSITION_ATTACHMENT, $fileName),
          'Content-Type' => 'application/vnd.ms-excel',
          'Cache-Control' => 'max-age=0',
        ]
      );

      return $response;
    }

    return $this->renderWithExtraParams('@App/admin/organization/export.html.twig', [
      'action' => 'export',
      'elements' => $this->admin->getShow(),
      'form' => $form->createView(),
    ], null);
  }
}
