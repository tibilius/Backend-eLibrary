<?php

namespace Library\UserBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Library\UserBundle\Entity\User as User;

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
     * @RequestParam(name="salt", nullable=false, strict=true, description="salt.")
     *
     * @return View
     */
    public function postTokenAction(ParamFetcher $paramFetcher)
    {

        $view = View::create();

        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($paramFetcher->get('username'));

        if (!$user instanceof User) {
            $view->setStatusCode(404)->setData("Data received succesfully but with errors.");

            return $view;
        }

        $factory = $this->get('security.encoder_factory');

        $encoder = $factory->getEncoder($user);
        $password = $encoder->encodePassword($paramFetcher->get('password'), $paramFetcher->get('salt'));
        $created = date('c');
        $nonce = substr(md5(uniqid('nonce_', true)), 0, 16);
        $nonceSixtyFour = base64_encode($nonce);
        $passwordDigest = $encoder->encodePassword($nonce . $created . $password, $paramFetcher->get('salt'));

        $token = sprintf(
            'UsernameToken Username="%s", PasswordDigest="%s", Nonce="%s", Created="%s"',
            $user,
            $passwordDigest,
            $nonceSixtyFour,
            $created
        );

        $data = array('X-WSSE' => $token);
        $view->setHeader("Authorization", 'WSSE profile="UsernameToken"');
        $view->setHeader("X-WSSE", $token);
        $view->setStatusCode(200)->setData($data);

        return $view;
    }

}
