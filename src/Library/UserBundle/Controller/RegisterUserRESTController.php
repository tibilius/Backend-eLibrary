<?php
namespace Library\UserBundle\Controller;

use Library\UserBundle\Entity\User;
use Library\UserBundle\Entity\Roles;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;

/**
 * Rest Controller
 */
class RegisterUserRESTController extends Controller
{
    /**
     * Create a new resource
     *
     * @param Request $request
     * @return View view instance
     *
     */
    public function postRegisterAction(Request $request)
    {

        $form = $this->container->get('fos_user.registration.form');
        $formHandler = $this->container->get('fos_user.registration.form.handler');
        $confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');
        $process = $formHandler->process($confirmationEnabled);
        if ($process) {
            $user = $form->getData();
            /**@var $user \Library\UserBundle\Entity\User*/
            $user->addRole(User::ROLE_READER);
            $em =$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $view = View::create($user, 201);
            return $this->get('fos_rest.view_handler')->handle($view);
        }
        $view = View::create($form, 400);
        return $this->get('fos_rest.view_handler')->handle($view);
    }
}