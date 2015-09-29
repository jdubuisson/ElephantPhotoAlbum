<?php

namespace Elephant\AlbumBundle\Controller;

use Elephant\AlbumBundle\Entity\Photo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Elephant\AlbumBundle\Entity\Album;
use Elephant\AlbumBundle\Form\AlbumType;
use Elephant\AlbumBundle\Form\AlbumEditType;
use Elephant\AlbumBundle\Form\AlbumShareType;
use Symfony\Component\Validator\Constraints\Collection;

/**
 * Album controller.
 *
 * @Route("/album")
 */
class AlbumController extends Controller
{

    /**
     * Lists all Album entities.
     *
     * @Route("/", name="album")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ElephantAlbumBundle:Album')->findByAuthor($this->getUser());

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Album entity.
     *
     * @Route("/", name="album_create")
     * @Method("POST")
     * @Template("ElephantAlbumBundle:Album:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Album();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setAuthor($this->getUser());
            foreach ($form->getData()->getPhotos() as $photo) {
                $photo->setAlbum($form->getData());
            }
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('album_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Album entity.
     *
     * @param Album $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Album $entity)
    {
        $form = $this->createForm(new AlbumEditType(), $entity, array(
            'action' => $this->generateUrl('album_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Album entity.
     *
     * @Route("/new", name="album_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Album();
        $entity->addPhoto(new Photo());
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'edit_form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Album entity.
     *
     * @Route("/{id}", name="album_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ElephantAlbumBundle:Album')->find($id);

        if($entity->getAuthor() != $this->getUser() && !$this->getUser()->getSharedAlbums()->contains($entity))
        {
            $this->addFlash('error','elephant.error.not_allowed');
            return $this->redirect($this->generateUrl('elephant_website_homepage'));
        }

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Album entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Album entity.
     *
     * @Route("/{id}/edit", name="album_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ElephantAlbumBundle:Album')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Album entity.');
        }
        if($entity->getAuthor() != $this->getUser())
        {
            $this->addFlash('error','elephant.error.not_allowed');
            return $this->redirect($this->generateUrl('elephant_website_homepage'));
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Album entity.
     *
     * @param Album $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Album $entity)
    {
        $form = $this->createForm(new AlbumEditType(), $entity, array(
            'action' => $this->generateUrl('album_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Album entity.
     *
     * @Route("/{id}", name="album_update")
     * @Method("PUT")
     * @Template("ElephantAlbumBundle:Album:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ElephantAlbumBundle:Album')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Album entity.');
        }
        if($entity->getAuthor() != $this->getUser())
        {
            throw $this->createNotFoundException('You are not allowed to edit this album');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            foreach ($editForm->getData()->getPhotos() as $photo) {
                $photo->setAlbum($editForm->getData());
            }
            $em->flush();

            return $this->redirect($this->generateUrl('album_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Album entity.
     *
     * @Route("/{id}", name="album_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ElephantAlbumBundle:Album')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Album entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('album'));
    }

    /**
     * Creates a form to delete a Album entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('album_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }

    /**
     * Displays a form to share an existing Album entity.
     *
     * @Route("/{id}/share", name="album_share")
     * @Method("GET")
     * @Template()
     */
    public function shareAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ElephantAlbumBundle:Album')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Album entity.');
        }

        $shareForm = $this->createShareForm($entity);

        return array(
            'entity' => $entity,
            'share_form' => $shareForm->createView()
        );
    }

    /**
     * Shares an existing Album entity.
     *
     * @Route("/{id}/share", name="album_update_share")
     * @Method("PUT")
     * @Template("ElephantAlbumBundle:Album:share.html.twig")
     */
    public function updateShareAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ElephantAlbumBundle:Album')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Album entity.');
        }

        $shareForm = $this->createShareForm($entity);
        $shareForm->handleRequest($request);

        if ($shareForm->isValid()) {
            foreach ($shareForm->getData()->getShares() as $share) {
                $share->setAlbum($shareForm->getData());
            }
            $em->flush();

            return $this->redirect($this->generateUrl('album_share', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $shareForm->createView()
        );
    }

    /**
     * Creates a form to share a Album entity.
     *
     * @param Album $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createShareForm(Album $entity)
    {
        $form = $this->createForm(new AlbumShareType(), $entity, array(
            'action' => $this->generateUrl('album_update_share', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Submit'));

        return $form;
    }
}
