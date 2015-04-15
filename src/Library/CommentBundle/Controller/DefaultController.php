<?php

namespace LibraryCommentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('LibraryCommentBundle:Default:index.html.twig', array('name' => $name));
    }
}
