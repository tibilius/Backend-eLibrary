<?php
namespace Library\UserBundle\Controller;

use Library\UserBundle\Entity\User;
use Library\UserBundle\Entity\Roles;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\RequestParam;

/**
 * Rest Controller
 */
class RegisterUserRESTController extends Controller
{
    /**
     * Create a new resource
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new user from the submitted data.",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     * @RequestParam(name="fos_user_registration_form[email]", nullable=false, strict=true, description="Email.")
     * @RequestParam(name="fos_user_registration_form[plainPassword][first]", nullable=false, strict=true, description="Password")
     * @RequestParam(name="fos_user_registration_form[plainPassword][second]", nullable=false, strict=true, description="Password repeat")
     * @RequestParam(name="fos_user_registration_form[firstName]", nullable=false, strict=true, description="First Name")
     * @RequestParam(name="fos_user_registration_form[middleName]", nullable=true, strict=true, description="Middle name.")
     * @RequestParam(name="fos_user_registration_form[lastName]", nullable=false, strict=true, description="Last name.")
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