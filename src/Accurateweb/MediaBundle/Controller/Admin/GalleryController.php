<?php

namespace Accurateweb\MediaBundle\Controller\Admin;

use Accurateweb\MediaBundle\Generator\ImageThumbnailGenerator;
use Accurateweb\MediaBundle\Model\Image\ImageAwareInterface;
use Accurateweb\MediaBundle\Model\Media\ImageInterface;
use Accurateweb\MediaBundle\Model\Media\Storage\MediaStorageInterface;
use Accurateweb\MediaBundle\Model\Thumbnail\ImageThumbnail;
use AppBundle\Entity\Store\Catalog\Product\ProductImage;
use AppBundle\Media\Image\PhotoProductImage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */
class GalleryController extends Controller
{
  public function listAction(Request $request)
  {
    $gallery = $this->get('aw.media.manager')->getGallery($request->get('gallery_provider_id'), $request->get('gallery_id'));

    $medias = $gallery->getMediaObjectManager()->getRepository()->getAll();

    $result = [];

    foreach ($medias as $media)
    {
      if ($media instanceof ImageAwareInterface)
      {
        $image = $media->getImage();
      }
      else
      {
        $image = $media;
      }

      $data = $this->imageToArray($image);

      if ($media instanceof ProductImage && $media->getYoutubeLink() && !$data['preview']['src'])
      {
        $data['preview']['src'] = '/images/youtube.jpg';
      }

      $result[] = $data;
    }

    return new JsonResponse($result);
  }

  public function deleteAction(Request $request)
  {
    $gallery = $this->get('aw.media.manager')->getGallery($request->get('gallery_provider_id'), $request->get('gallery_id'));

    $mediaObjectManager = $gallery->getMediaObjectManager();

    $media = $mediaObjectManager->getRepository()->find($request->request->get('media_id'));

    if (!$media)
    {
      throw new NotFoundHttpException();
    }

    /** @var MediaStorageInterface $storage */
    $storage = $this->get('aw.media.storage.provider')->getMediaStorage($media);
    $storage->remove($media);

    $mediaObjectManager->remove($media);
    $mediaObjectManager->flush();

    return new JsonResponse();
  }

  protected function imageToArray(ImageInterface $media)
  {
    $thumbnail = new ImageThumbnail('preview', $media);

    /** @var MediaStorageInterface $mediaStorage */
    $mediaStorage = $this->get('aw.media.storage.provider')->getMediaStorage($thumbnail);

    $thumbnailResource = $mediaStorage->retrieve($thumbnail);
    $mediaResource = $mediaStorage->retrieve($media);

    $preview_src = $thumbnailResource ? $thumbnailResource->getUrl() : null;

//    if (!$preview_src && $media->getYoutubeLink())
//    {
//      $preview_src = '/images/youtube.jpg';
//    }

    return [
      'id' => $media->getId(),
      'crop' => array(null, null, null, null),
      'preview' => array(
        'id' => $thumbnail->getId(),
        'src' => $preview_src
      ),
      'src' => $mediaResource ? $mediaResource->getUrl() : null
    ];
  }
}