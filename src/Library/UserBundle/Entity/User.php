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
     * @ORM\Column(type="string", nullable=false)
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", nullable=false)
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
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
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


}
