<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Obrashcheniya\ObrashcheniyaFile;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class ObrashcheniyaController extends Controller
{
  /**
   * @Route("/appeals/{id}/download", name="appeal_download")
   */
  public function appealDownloadAction(Request $request)
  {
    $user = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $obrashcheniyaFile = $em->getRepository(ObrashcheniyaFile::class)
      ->createFileQueryBuilder($request->get('id'), $request->get('image_number'), ($user->getIsAdmin() ? null : $user))
      ->setMaxResults(1)
      ->getQuery()
      ->getOneOrNullResult();

    if (!$obrashcheniyaFile)
    {
      throw $this->createNotFoundException(sprintf('Obrashcheniya File model not found'));
    }

    try
    {
      $response = new BinaryFileResponse($obrashcheniyaFile->getFile());
      $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_INLINE, $obrashcheniyaFile->getFileName());
    }
    catch (FileNotFoundException $e)
    {
      throw $this->createNotFoundException(sprintf('Obrashcheniya file not found'));
    }

    return $response;
  }
}
