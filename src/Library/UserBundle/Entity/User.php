<?php

namespace Library\UserBundle\Entity;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;
use Gedmo\Mapping\Annotation as Gedmo;
use Library\CatalogBundle\DBAL\Types\ReadlistEnumType;
use Library\CatalogBundle\Entity\Readlists;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use JMS\Serializer\Annotation\Groups;

/**
 * User
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="users")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity()
 */
class User extends BaseUser
{
    const ROLE_GUEST = 'ROLE_GUEST';
    const ROLE_READER = 'ROLE_READER';
    const ROLE_GROSSMEISER = 'ROLE_GROSSMEISER';
    const ROLE_EXPERT = 'ROLE_EXPERT';

    static $userRoles = [
        self::ROLE_GUEST => 'Гость',
        self::ROLE_READER => 'Читатель',
        self::ROLE_EXPERT => 'Эксперт',
        self::ROLE_GROSSMEISER => 'Гроссмейстер',
        self::ROLE_SUPER_ADMIN => 'Администратор',
    ];

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"id", "user"})
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
     * @Groups({"user"})
     * @var File $avatarImage
     */
    protected $avatarImage;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"user"})
     */
    protected $phone;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"user"})
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"user"})
     */
    protected $lastName;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"user"})
     */
    protected $middleName;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @Groups({"user"})
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @Groups({"user"})
     */
    protected $updated;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":"TRUE"})
     * @Groups({"user"})
     * @var boolean
     */
    protected $moderated = true;

    /**
     * @ORM\Column(type="datetime", nullable=true, name="time_readed_comments", options={"default":"NOW()"})
     * @Groups({"user"})
     * @var \DateTime
     */
    protected $timeReadedComments;

    /**
     * @return array
     */
    public static function getUserRoles()
    {
        return static::$userRoles;
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
        $email = urldecode(strtolower($email));
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


    /**
     * @return File
     */
    public function getAvatarImage()
    {
        return $this->avatarImage;
    }

    /**
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     * @return User
     */
    public function setAvatarImage(File $image = null)
    {
        $this->avatarImage = $image;
        if ($image) {
            $this->setUpdated(new \DateTime());
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     * @return User
     */
    public function setCreated($created)
    {
        $this->created = $created;
        $this->setUpdated($created);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param mixed $updated
     * @return User
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * @return mixed
     */
    public function isModerated()
    {
        return $this->moderated;
    }

    /**
     * @param mixed $moderated
     * @return User
     */
    public function setModerated($moderated)
    {
        $this->moderated = $moderated;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTimeReadedComments()
    {
        return $this->timeReadedComments;
    }

    /**
     * @param string $timeReadedComments
     * @return User
     */
    public function setTimeReadedComments($timeReadedComments)
    {
        $this->timeReadedComments = new \DateTime($timeReadedComments);
        return $this;
    }

    /**
     * @ORM\PrePersist
    */
    public function onPrePersist(LifecycleEventArgs $args) {
        $this->setCreated(new \DateTime());
        $em = $args->getObjectManager();
        $this->_createInternalReadlists(ReadlistEnumType::getInternalTypes(), $em);
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate(LifecycleEventArgs $args) {
        $this->setUpdated(new \DateTime());
    }

    protected function _createInternalReadlists(array $types, EntityManagerInterface $em) {
        foreach($types as $type) {
            $readlist =  new Readlists();
            $readlist
                ->setUser($this)
                ->setType($type)
                ->setName(ReadlistEnumType::getChoices()[$type])
                ->setColor(ReadlistEnumType::getColors()[$type]);
            $em->persist($readlist);
        }
    }


}
