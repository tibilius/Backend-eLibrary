<?php
namespace Library\UserBundle\Security\Core\Authentication\Provider;


use Escape\WSSEAuthenticationBundle\Security\Core\Authentication\Provider\Provider;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\CredentialsExpiredException;
use Symfony\Component\Security\Core\Exception\NonceExpiredException;

/**
 * Class WsseProvider
 * @package Library\UserBundle\Security\Core\Authentication\Provider
 */
class WsseProvider extends Provider implements AuthenticationProviderInterface
{
    protected function validateDigest($digest, $nonce, $created, $secret, $salt)
    {
        //check whether timestamp is formatted correctly
        if(!$this->isFormattedCorrectly($created))
        {
            die('3');
            throw new BadCredentialsException('Incorrectly formatted "created" in token.');
        }

        //check whether timestamp is not in the future
        if($this->isTokenFromFuture($created))
        {
            die('2');
            throw new BadCredentialsException('Future token detected.');
        }

        //expire timestamp after specified lifetime
        if(strtotime($this->getCurrentTime()) - strtotime($created) > $this->getLifetime())
        {
            die('1');
            throw new CredentialsExpiredException('Token has expired.');
        }

        /// protection only by lifetime
        //validate that nonce is unique within specified lifetime
        //if it is not, this could be a replay attack
        if($this->getNonceCache()->contains($nonce . $secret))
        {
            throw new NonceExpiredException('Previously used nonce detected.');
        }
        $this->getNonceCache()->save($nonce . $secret, strtotime($this->getCurrentTime()), $this->getLifetime());
        //validate secret
        $expected = $this->getEncoder()->encodePassword(
            sprintf(
                '%s%s%s',
                base64_decode($nonce),
                $created,
                $secret
            ),
            $salt
        );
        return $digest === $expected;
    }
}