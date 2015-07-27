<?php
namespace Library\UserBundle\Security\Core\User;

use HWI\Bundle\OAuthBundle\OAuth\Response\PathUserResponse;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Library\UserBundle\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

class FOSUBUserProvider extends BaseClass
{

    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $property = $this->getProperty($response);
        $username = $response->getUsername();

//on connect - get the access token and the user ID
        $service = $response->getResourceOwner()->getName();

        $setter = 'set' . ucfirst($service);
        $setter_id = $setter . 'Id';
        $setter_token = $setter . 'AccessToken';

//we "disconnect" previously connected users
        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }

//we connect current user
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());

        $this->userManager->updateUser($user);
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {

        $username = $response->getUsername();
        /**@var $user \Library\UserBundle\Entity\User*/
        $user = $this->userManager->findUserBy(array($this->getProperty($response) => $username));
//when the user is registrating
        if (null === $user) {
            $service = $response->getResourceOwner()->getName();
            $setter = 'set' . ucfirst($service);
            $setter_id = $setter . 'Id';
            $setter_token = $setter . 'AccessToken';
// create new user here
            $user = $this->userManager->createUser();
            $user->$setter_id($username);
            $user->$setter_token($response->getAccessToken());
//I have set all requested data with the user's username
//modify here with relevant data
            $user->setEmail($response->getEmail());
            $user->setModerated(false);
            $user->setPassword(base64_encode(sha1($username. $user->getSalt())));
            $user->setEnabled(true);
            $date = new \DateTime();
            $user->setCreated($date)->setUpdated($date);
            list($firstName, $lastName, $middleName) = explode(' ', $response->getRealName(), 3) + [ 2 => ''];
            $user->setFirstName($firstName);
            $user->setLastName($lastName);
            $user->setMiddleName($middleName);
            $user->setTimeReadedComments("now");
            $user->addRole(User::ROLE_GUEST);
            $this->userManager->updateUser($user);
            return $user;
        }

//if user exists - go with the HWIOAuth way
        $user = parent::loadUserByOAuthUserResponse($response);

        $serviceName = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($serviceName) . 'AccessToken';

//update access token
        $user->$setter($response->getAccessToken());
        return $user;
    }

}