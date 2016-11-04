<?php

namespace GalleryBundle\Controller;

use GalleryBundle\Entity\Album;
use GalleryBundle\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class AlbumController extends Controller
{
    /**
     * Lists all album entities.
     *
     * @Route("/", name="_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $albums = $em->getRepository('GalleryBundle:Album')->findAll();

        return $this->render('album/index.html.twig', array(
            'albums' => $albums,
        ));
    }

    /**
     * Lists  album entities wich contain up to 10 images .
     *
     * @Route("/albumList", name="_albumList")
     * @Method({"GET", "POST"})
     */
    public function albumListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $albums = $em->getRepository('GalleryBundle:Album')->findAll();
        $albumList = array();
        foreach ($albums as $album) {
            $images = $em->getRepository('GalleryBundle:Image')->findBy(array('album_id' => $album->getId()));
            if (count($images) < 10) {
                $albumList[] = $album;
            }
        }
        return $this->render('album/albumList.html.twig', array(
            'albumList' => $albumList,
        ));
    }

    /**
     * Lists all image entities.
     *
     * @Route("/{id}/imageList", name="_imageList")
     * @Method({"GET", "POST"})
     */
    public function imageListAction(Request $request, Album $album)
    {
        $em = $this->getDoctrine()->getManager();
        $images = $em->getRepository('GalleryBundle:Image')->findBy(array('album_id' => $album->getId()));

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $images,
            $request->query->getInt('page', 1), 10);
        return $this->render('album/imageList.html.twig', array(
            'album' => $album,
            'images' => $images,
            'pagination' => $pagination
        ));
    }

    /**
     * Creates a new image entity.
     *
     * @Route("/{id}/newImage", name="_newImage")
     * @Method({"GET", "POST"})
     */
    public function newImageAction(Request $request, Album $album)
    {
        $image = new Image();
        $form = $this->createForm('GalleryBundle\Form\ImageType', $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $image->setAlbumId($album);
            $em->persist($image);
            $em->flush($image);

            return $this->redirectToRoute('_imageList', array('id' => $album->getId()));
        }
        return $this->render('album/newImage.html.twig', array(
            'image' => $image,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new album entity.
     *
     * @Route("/new", name="_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $album = new Album();
        $form = $this->createForm('GalleryBundle\Form\AlbumType', $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($album);
            $em->flush($album);

            return $this->redirectToRoute('_show', array('id' => $album->getId()));
        }

        return $this->render('album/new.html.twig', array(
            'album' => $album,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a album entity.
     *
     * @Route("/{id}", name="_show")
     * @Method("GET")
     */
    public function showAction(Album $album)
    {
        // $deleteForm = $this->createDeleteForm($album);
        $em = $this->getDoctrine()->getManager();
        $images = $em->getRepository('GalleryBundle:Image')->findBy(array('album_id' => $album->getId()));
        return $this->render('album/show.html.twig', array(
            'album' => $album,
            'images' => $images,
        ));
    }

    /**
     * Displays a form to edit an existing album entity.
     *
     * @Route("/{id}/edit", name="_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Album $album)
    {
        $deleteForm = $this->createDeleteForm($album);
        $editForm = $this->createForm('GalleryBundle\Form\AlbumType', $album);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('_edit', array('id' => $album->getId()));
        }

        return $this->render('album/edit.html.twig', array(
            'album' => $album,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a album entity.
     *
     * @Route("/{id}", name="_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Album $album)
    {
        $form = $this->createDeleteForm($album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($album);
            $em->flush($album);
        }

        return $this->redirectToRoute('_index');
    }

    /**
     * Creates a form to delete a album entity.
     *
     * @param Album $album The album entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Album $album)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('_delete', array('id' => $album->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
