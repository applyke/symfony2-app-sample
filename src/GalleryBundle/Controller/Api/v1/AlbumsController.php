<?php

namespace GalleryBundle\Controller\Api\v1;

use GalleryBundle\Entity\Album;
use GalleryBundle\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncode;

class AlbumsController extends Controller
{
    /**
     * Albums api controller.
     *
     * @Route("/api/v1/albums", name="_apiAlbums")
     * @Method("GET")
     */
    public function allAlbumAction()
    {
        $normalizer = new GetSetMethodNormalizer();
        $normalizer->setIgnoredAttributes(array('images', 'created', 'updated'));
        $encoder = new JsonEncoder(new JsonEncode(JSON_UNESCAPED_SLASHES), new JsonDecode(false));
        $serializer = new Serializer(array($normalizer), array($encoder));
        $em = $this->getDoctrine()->getManager();

        $albums = $em->getRepository('GalleryBundle:Album')->findAll();
        $albumList = array();
        if ($albums) {
            foreach ($albums as $album) {
                $albumList[] = array(
                    'album' => $album,
                    'images' => $this->getImageListAction($album->getId())
                );
            }
            return new Response($serializer->serialize($albumList, 'json'));
        }
        return new Response('Albums not found');
    }

    public function getImageListAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $images = $em->getRepository('GalleryBundle:Image')->findBy(array('album_id' => $id));
        if ($images) {
            $count = count($images);
            if (count($images) > 10) {
                $start = count($images) - 10;
            } else {
                $start = 0;
            }
            for ($i = $start; $i < $count; $i++) {
                $imageList[] = array(
                    'id' => $images[$i]->getId(),
                    'path' => $images[$i]->getPath(),
                    'url' => $images[$i]->getUrl(),
                    'width' => $images[$i]->getWidth(),
                    'height' => $images[$i]->getHeight(),
                    'created' => $images[$i]->getCreated(),
                );
            }
            $response = array('imageList' => $imageList);
            return $response;
        }
        return new Response('This album doesn\'t have images');
    }

    /**
     * Albums api controller.Images list.
     *
     * @Route("/api/v1/{id}/images", name="_apiImageList")
     * @Method("GET")
     */
    public function getImageByAlbumIdAction($id)
    {
        $normalizer = new GetSetMethodNormalizer();
        $normalizer->setIgnoredAttributes(array('created', 'albumId'));
        $encoder = new JsonEncoder(new JsonEncode(JSON_UNESCAPED_SLASHES), new JsonDecode(false));
        $serializer = new Serializer(array($normalizer), array($encoder));
        $em = $this->getDoctrine()->getManager();

        $images = $em->getRepository('GalleryBundle:Image')->findBy(array('album_id' => $id));
        if ($images) {
            foreach ($images as $image) {
                $imageList[] = $image;
            }
            return new Response($serializer->serialize($imageList, 'json'));
        }
        return new Response('This album not found or it doesn\'t  have images');
    }

    protected function prepareAlbumObject(\GalleryBundle\Entity\Album $album)
    {
        return array(
            'id' => $album->getId(),
            'title' => $album->getTitle(),
            'code' => $album->getCode(),
            'status' => $album->getStatus(),
            'created' => $album->getCreated(),
        );
    }
}