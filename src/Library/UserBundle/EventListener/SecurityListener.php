<?php
namespace Library\UserBundle\EventListener;

use Symfony\Component\Security\Core\SecurityContext;

class SecurityListener {

    protected $securityContext;

    public function __construct(SecurityContext $securityContext)
    {
        $this->securityContext = $securityContext;
        return $this;
    }


    public function onIntaractiveLogin($event) {
        if(!$this->securityContext->isGranted('IS_AUTHENTICATED_ANONYMOUSLY')) {
            $this->securityContext->setToken(null);
        }
    }
}