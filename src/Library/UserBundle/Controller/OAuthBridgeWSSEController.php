<?php
namespace Library\UserBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class OAuthBridgeWSSEController extends Controller
{

    public function bridgeAction(Request $request)
    {
        /**
         * @var $user \Library\UserBundle\Entity\User
         */
        $user = $this->getUser();
        if (!$user->getFacebookAccessToken() && !$user->getFacebookAccessToken()) {
            $route = $this->container->getParameter('front_url') . '/login';
        }
        else{
            $params = ['username' => $user->getUsername(), 'password' => $user->getPassword()];
            $route = $this->container->getParameter('front_url') . '/auth?' . http_build_query($params);
        }
        return new RedirectResponse($route);
    }

}