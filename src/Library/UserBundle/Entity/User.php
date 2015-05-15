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
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
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
        return parent::setEmail($email);
    }

    public function setUsername($username)
    {
        $this->setUsernameCanonical($username);
        return parent::setUsername($username);
    }


}
