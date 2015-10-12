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

        return array(
            'entity' => $entity,
        );
    }
}
