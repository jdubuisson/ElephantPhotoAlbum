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
     * Finds and displays a Album entity.
     *
     * @Route("/{id}", name="album_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ElephantAlbumBundle:Album')->find($id);

        if ($entity->getAuthor() != $this->getUser() && !$this->getUser()->getSharedAlbums()->contains($entity)) {
            $this->addFlash('error', 'elephant.error.not_allowed');
            return $this->redirect($this->generateUrl('elephant_website_homepage'));
        }

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Album entity.');
        }

        // Comments and Thread management
        $thread = $this->container->get('fos_comment.manager.thread')->findThreadById($id);
        if (null === $thread) {
            $thread = $this->container->get('fos_comment.manager.thread')->createThread();
            $thread->setId($id);
            $thread->setPermalink($request->getUri());

            // Add the thread
            $this->container->get('fos_comment.manager.thread')->saveThread($thread);
        }

        $comments = $this->container->get('fos_comment.manager.comment')->findCommentTreeByThread($thread);

        return array(
            'entity' => $entity,
            'comments' => $comments,
            'thread' => $thread,
        );
    }
}
