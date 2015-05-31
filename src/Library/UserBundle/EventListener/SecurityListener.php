<?php
namespace Library\UserBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\SecurityContext;

class SecurityListener {

    protected $securityContext;

    public function __construct(SecurityContext $securityContext)
    {
        $this->securityContext = $securityContext;
        return $this;
    }


    public function onIntaractiveLogin(GetResponseEvent $event) {
        $uri = $event->getRequest()->attributes->get('_route');
        $token = $this->securityContext->getToken();
        if ($uri === 'hwi_oauth_service_redirect' && !($token instanceof AnonymousToken)) {
            $event->getRequest()->getSession()->invalidate();
        }
    }
}