<?php

namespace Library\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * User
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="users")
 * @ORM\Entity()
 */
class User extends BaseUser
{
    const ROLE_GUEST = 'ROLE_GUEST';
    const ROLE_READER = 'ROLE_READER';
    const ROLE_GROSSMEISER = 'ROLE_GROSSMEISER';
    const ROLE_EXPERT = 'ROLE_EXPERT';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="facebook_id", type="string", length=255, nullable=true)
     */
    protected $facebook_id;

    /**
     * @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true)
     */
    protected $facebook_access_token;

    /**
     * @ORM\Column(name="vkontakte_id", type="string", length=255, nullable=true)
     */
    protected $vkontakte_id;

    /**
     * @ORM\Column(name="vkontakte_access_token", type="string", length=255, nullable=true)
     */
    protected $vkontakte_access_token;

    /**
     *
     * @Orm\Column(type="string", nullable=true)
     *
     * @var string $avatar
     */
    protected $avatar;

    /**
     *
     * @Vich\UploadableField(mapping="user_image", fileNameProperty="avatar")
     *
     * @var File $avatarImage
     */
    protected $avatarImage;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $phone;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $lastName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $middleName;

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     */
    public function setImageFile(File $image = null)
    {
        $this->avatarImage = $image;

        if ($image) {
            $this->updated = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->avatarImage;
    }

    public function setEmail($email)
    {
        $email = strtolower($email);
        $this->setEmailCanonical($email);
        $this->setUsername($email);
        return parent::setEmail($email);
    }

    public function setUsername($username)
    {
        $this->setUsernameCanonical($username);
        return parent::setUsername($username);
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * @param mixed $middleName
     * @return User
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFacebookId()
    {
        return $this->facebook_id;
    }

    /**
     * @param mixed $facebook_id
     * @return User
     */
    public function setFacebookId($facebook_id)
    {
        $this->facebook_id = $facebook_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFacebookAccessToken()
    {
        return $this->facebook_access_token;
    }

    /**
     * @param mixed $facebook_access_token
     * @return User
     */
    public function setFacebookAccessToken($facebook_access_token)
    {
        $this->facebook_access_token = $facebook_access_token;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVkontakteId()
    {
        return $this->vkontakte_id;
    }

    /**
     * @param mixed $vkontakte_id
     * @return User;
     */
    public function setVkontakteId($vkontakte_id)
    {
        $this->vkontakte_id = $vkontakte_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVkontakteAccessToken()
    {
        return $this->vkontakte_access_token;
    }

    /**
     * @param mixed $vkontakte_access_token
     * @return User
     */
    public function setVkontakteAccessToken($vkontakte_access_token)
    {
        $this->vkontakte_access_token = $vkontakte_access_token;
    }




}
