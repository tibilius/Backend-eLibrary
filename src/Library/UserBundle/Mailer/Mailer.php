<?php
namespace Library\UserBundle\Mailer;


use Library\UserBundle\Entity\User;

class Mailer extends \FOS\UserBundle\Mailer\Mailer {

    public function sendNewPasswordMessage(User $user, $plainPassword) {
        $template = $this->parameters['resetting.template'];
        $footerSign = $this->parameters['from_email']['footer_sign'];
        $rendered = $this->templating->render($template, compact('user', 'plainPassword','footerSign'));
        $this->sendEmailMessage($rendered, $this->parameters['from_email']['confirmation'], $user->getEmail());
    }
}