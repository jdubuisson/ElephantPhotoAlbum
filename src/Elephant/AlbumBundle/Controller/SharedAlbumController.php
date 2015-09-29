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
 * @Route("/shared_album")
 */
class SharedAlbumController extends Controller
{

    /**
     * Lists all shared Album entities.
     *
     * @Route("/", name="shared_album")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $shares = $em->getRepository('ElephantAlbumBundle:Share')->findByEmail($this->getUser()->getEmail());

        foreach($shares as $share)
        {
            $album = $share->getAlbum();
            $this->getUser()->addSharedAlbum($album);
            $em->persist($this->getUser());
            $em->remove($share);
        }
        $em->flush();
        $entities = $this->getUser()->getSharedAlbums();

        return array(
            'entities' => $entities,
        );
    }
}
