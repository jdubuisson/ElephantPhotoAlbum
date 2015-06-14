<?php

namespace Elephant\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {

        return $this->render('ElephantWebsiteBundle:Default:index.html.twig', array());
    }
    public function aboutAction()
    {

        return $this->render('ElephantWebsiteBundle:Default:about.html.twig', array());
    }
}
