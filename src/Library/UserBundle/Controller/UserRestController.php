<?php

namespace Library\UserBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcher;
use Library\UserBundle\Entity\User;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\Validator\ConstraintViolationList;

class UserRestController extends FOSRestController
{

    /**
     * Return the overall user list.
     *
     * @Secure(roles="ROLE_READER")
     * @ApiDoc(
     *   resource = true,
     *   description = "Return the current loginned user",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     401 = "Returned when the user is not found"
     *   }
     * )
     *
     * @return View
     */
    public function getWhoamiAction() {
        $currentUser = $this->container->get('security.context')->getToken()->getUser();
        $user = $this->container->get('fos_user.user_manager')->findUserBy(['id'=>$currentUser->getId()]);
        $view = View::create($user, 200);
        return $view;
    }


    /**
     * Return the overall user list.
     *
     * @Secure(roles="ROLE_SUPER_ADMIN")
     * @ApiDoc(
     *   resource = true,
     *   description = "Return the overall User List",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the user is not found"
     *   }
     * )
     *
     * @return View
     */
    public function getUsersAction()
    {

        $userManager = $this->container->get('fos_user.user_manager');
        $entity = $userManager->findUsers();

        if (!$entity) {
            throw $this->createNotFoundException('Data not found.');
        }

        $view = View::create();
        $view->setData($entity)->setStatusCode(200);

        return $view;
    }

    /**
     * Return an user identified by id.
     * @Secure(roles="ROLE_READER")
     * @ApiDoc(
     *   resource = true,
     *   description = "Return an user identified by ID",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the user is not found"
     *   }
     * )
     *
     * @param string $id identifier
     *
     * @return View
     */
    public function getUserAction($id)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        /**
         * @var \Library\UserBundle\Entity\User $entity
         */
        $entity = $userManager->findUserBy(array("id" => $id));

        if (!$entity) {
            throw $this->createNotFoundException('Data not found.');
        }

        $view = View::create();
        $view->setData($entity)->setStatusCode(200);
        return $view;
    }

    /**
     * Create a User from the submitted data.<br/>
     *
     * @Secure(roles="ROLE_SUPER_ADMIN")
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new user from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     *
     * @RequestParam(name="username", nullable=false, strict=true, description="Username.")
     * @RequestParam(name="email", nullable=false, strict=true, description="Email.")
     * @RequestParam(name="firstname", nullable=false, strict=true, description="Name.")
     * @RequestParam(name="lastname", nullable=false, strict=true, description="Lastname.")
     * @RequestParam(name="middlename", nullable=true, strict=true, description="Middle name.")
     * @RequestParam(name="password", nullable=false, strict=true, description="Plain Password.")
     *
     * @return View
     */
    public function postUserAction(ParamFetcher $paramFetcher)
    {

        $userManager = $this->container->get('fos_user.user_manager');
        /**
         * @var \Library\UserBundle\Entity\User $user
         */
        $user = $userManager->createUser();
        $user->setUsername($paramFetcher->get('username'));
        $user->setEmail($paramFetcher->get('email'));
        $user->setPlainPassword($paramFetcher->get('password'));
        $user->setFirstName($paramFetcher->get('firstname'));
        if($paramFetcher->get('middlename')){
            $user->setMiddlename($paramFetcher->get('middlename'));
        }
        $user->setLastname($paramFetcher->get('lastname'));
        $user->setEnabled(true);
        $user->addRole(User::ROLE_READER);
        $view = View::create();

        $errors = $this->get('validator')->validate($user, array('Registration'));

        if (count($errors) == 0) {
            $userManager->updateUser($user);
            $view->setData($user)->setStatusCode(200);
            return $view;
        } else {
            $view = $this->getErrorsView($errors);
            return $view;
        }
    }

    /**
     * Update a User from the submitted data by ID.<br/>
     *
     * @Secure(roles="ROLE_USER")
     * @ApiDoc(
     *   resource = true,
     *   description = "Updates a user from the submitted data by ID.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     *
     * @RequestParam(name="id", nullable=false, strict=true, description="id.")
     * @RequestParam(name="username", nullable=true, strict=true, description="Username.")
     * @RequestParam(name="email", nullable=true, strict=true, description="Email.")
     * @RequestParam(name="firstname", nullable=true, strict=true, description="Name.")
     * @RequestParam(name="lastname", nullable=true, strict=true, description="Lastname.")
     * @RequestParam(name="middlename", nullable=true, strict=true, description="Middlename.")
     * @RequestParam(name="password", nullable=true, strict=true, description="Plain Password.")
     *
     * @return View
     */
    public function putUserAction(ParamFetcher $paramFetcher)
    {

        $currentUser = $this->container->get('security.context')->getToken()->getUser();
        if ($currentUser->getId() !=  $paramFetcher->get('id')) {
            return  View::create(null, 403);
        }

        $entity = $this->getDoctrine()->getRepository('UserBundle:User')->findOneBy(
            array('id' => $paramFetcher->get('id'))
        );

        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($entity->getUsername());

        if($paramFetcher->get('username')){ $user->setUsername($paramFetcher->get('username')); }
        if($paramFetcher->get('email')){$user->setEmail($paramFetcher->get('email')); }
        if($paramFetcher->get('password')){$user->setPlainPassword($paramFetcher->get('password')); }
        if($paramFetcher->get('firstname')){$user->setFirstname($paramFetcher->get('firstname')); }
        if($paramFetcher->get('lastname')){$user->setLastname($paramFetcher->get('lastname')); }
        if($paramFetcher->get('middlename')){$user->setLastname($paramFetcher->get('middlename')); }

        $view = View::create();

        $errors = $this->get('validator')->validate($user, array('Update'));

        if (count($errors) == 0) {
            $userManager->updateUser($user);
            $view->setData($user)->setStatusCode(200);
            return $view;
        } else {
            $view = $this->getErrorsView($errors);
            return $view;
        }
    }

    /**
     * Delete an user identified by username/email.
     *
     * @Secure(roles="ROLE_READER")
     * @ApiDoc(
     *   resource = true,
     *   description = "Delete an user identified by username/email",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the user is not found"
     *   }
     * )
     *
     * @param string $slug username or email
     *
     * @return View
     */
    public function deleteUserAction($slug)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        $entity = $userManager->findUserByUsernameOrEmail($slug);

        if (!$entity) {
            throw $this->createNotFoundException('Data not found.');
        }

        $userManager->deleteUser($entity);

        $view = View::create();
        $view->setData("User deteled.")->setStatusCode(204);

        return $view;
    }

    /**
     * Get User Salt.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Get user salt by its username",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the user is not found"
     *   }
     * )
     *
     * @param string $id the user username
     *
     * @return View
     */
    public function getUserSaltAction($slug)
    {

        $entity = $this->getDoctrine()->getRepository('Library\UserBundle\Entity\User')->findOneBy(
            array('username' => $slug)
        );

        if (!$entity) {
            throw $this->createNotFoundException('Data not found.');
        }

        $salt = $entity->getSalt();

        $view = View::create();
        $view->setData(array('salt' => $salt))->setStatusCode(200);

        return $view;
    }


    /**
     * Get the validation errors
     *
     * @param ConstraintViolationList $errors Validator error list
     *
     * @return View
     */
    protected function getErrorsView(ConstraintViolationList $errors)
    {
        $msgs = array();
        $errorIterator = $errors->getIterator();
        foreach ($errorIterator as $validationError) {
            $msg = $validationError->getMessage();
            $params = $validationError->getMessageParameters();
            $msgs[$validationError->getPropertyPath()][] = $this->get('translator')->trans($msg, $params, 'validators');
        }
        $view = View::create($msgs);
        $view->setStatusCode(400);

        return $view;
    }

}
