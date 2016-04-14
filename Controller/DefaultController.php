<?php

namespace abdielcs\ExpandedCollectionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ExpandedCollectionBundle:Default:index.html.twig', array('name' => $name));
    }
}
