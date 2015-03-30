<?php

namespace library\RestLibraryBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
/*
 *
 */
class BookController extends FOSRestController
{
    public function getBookAction($id){
        $user = $this->getDoctrine()->getRepository('UserBundle:User')->findOneByUsername($username);
        if(!is_object($user)){
            throw $this->createNotFoundException();
        }
        return $user;
    }

    public function updateBookAction($id){
        $request = $this->getRequest();
    }
}