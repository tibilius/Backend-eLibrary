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
        list($type, $token) = $user->getFacebookAccessToken()
            ? ['facebook', $user->getFacebookAccessToken()]
            : ['vkontakte', $user->getVkontakteAccessToken()];
        $route = $this->container->getParameter('front_url') . '/auth?' . http_build_query(compact('type', 'token'));
        return new RedirectResponse($route);
    }

}