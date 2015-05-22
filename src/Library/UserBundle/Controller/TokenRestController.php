<?php

namespace Library\UserBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Library\UserBundle\Entity\User as User;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\RequestParam;

class TokenRestController extends FOSRestController
{

    /**
     * Create a Token from the submitted data.<br/>
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new token from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     *
     * @RequestParam(name="username", nullable=false, strict=true, description="username.")
     * @RequestParam(name="password", nullable=false, strict=true, description="password.")
     *
     * @return View
     */
    public function postTokenAction(ParamFetcher $paramFetcher)
    {

        $view = View::create();

        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($paramFetcher->get('username'));
        if (!$user instanceof User) {
            $view->setStatusCode(404)->setData("User not found");
            return $view;
        }
        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);
        $password = $encoder->encodePassword($paramFetcher->get('password'), $user->getSalt());
        if ($password !== $user->getPassword()) {
            $view->setStatusCode(404)->setData("Incorrect credentials");
            return $view;
        }
        list($data, $token) = $this->getWSSEHeader($user);
        $view->setHeader("Authorization", 'WSSE profile="UsernameToken"');
        $view->setHeader("X-WSSE", $token);
        $view->setStatusCode(200)->setData($data);
        return $view;
    }

    /**
     * Create a Token from the submitted data.<br/>
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new token from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     *
     * @RequestParam(name="type", nullable=false, strict=true, description="facebook or vkontakte")
     * @RequestParam(name="token", nullable=false, strict=true, description="token")
     *
     * @return View
     */
    public function postOauthTokenAction(ParamFetcher $paramFetcher)
    {
        $view = View::create();
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserBy([
            $paramFetcher->get('type') . '_access_token' => $paramFetcher->get('token'),
        ]);
        if (!$user instanceof User) {
            $view->setStatusCode(404)->setData("User not found");
            return $view;
        }
        $password = $user->getPassword();
        if ($password !== $user->getPassword()) {
            $view->setStatusCode(404)->setData("Data received succesfully but with errors.");
            return $view;
        }
        list($data, $token) = $this->getWSSEHeader($user);
        $view->setHeader("Authorization", 'WSSE profile="UsernameToken"');
        $view->setHeader("X-WSSE", $token);
        $view->setStatusCode(200)->setData($data);
        return $view;
    }

    /**
     * Create a Token from the submitted data.<br/>
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new token from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     * @return View
     */
    public function getTimeAction(ParamFetcher $paramFetcher)
    {
        return View::create([
            'time' => date('c'),
            'token_lifetime' => $this->container->get('escape_wsse_authentication.provider')->getLifetime(),
        ], 200);
    }

    protected function getWSSEHeader(User $user)
    {
        $created = date('c');
        $nonce = substr(md5(uniqid('nonce_', true)), 0, 16);
        $nonceSixtyFour = base64_encode($nonce);
        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);
        $passwordDigest = $encoder->encodePassword($nonce . $created . $user->getPassword(), $user->getSalt());
        $token = sprintf(
            'UsernameToken Username="%s", PasswordDigest="%s", Nonce="%s", Created="%s"',
            $user,
            $passwordDigest,
            $nonceSixtyFour,
            $created
        );
        return [['X-WSSE' => $token], $token];
    }
}
