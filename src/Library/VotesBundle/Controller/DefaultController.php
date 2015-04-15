<?php

namespace LibraryVotesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('LibraryVotesBundle:Default:index.html.twig', array('name' => $name));
    }
}
