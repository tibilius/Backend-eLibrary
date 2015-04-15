<?php

namespace Acme\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Thread
 */
class Thread
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $permalink;

    /**
     * @var boolean
     */
    private $isCommentable;

    /**
     * @var integer
     */
    private $numComments;

    /**
     * @var \DateTime
     */
    private $lastCommentAt;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set permalink
     *
     * @param string $permalink
     * @return Thread
     */
    public function setPermalink($permalink)
    {
        $this->permalink = $permalink;

        return $this;
    }

    /**
     * Get permalink
     *
     * @return string 
     */
    public function getPermalink()
    {
        return $this->permalink;
    }

    /**
     * Set isCommentable
     *
     * @param boolean $isCommentable
     * @return Thread
     */
    public function setIsCommentable($isCommentable)
    {
        $this->isCommentable = $isCommentable;

        return $this;
    }

    /**
     * Get isCommentable
     *
     * @return boolean 
     */
    public function getIsCommentable()
    {
        return $this->isCommentable;
    }

    /**
     * Set numComments
     *
     * @param integer $numComments
     * @return Thread
     */
    public function setNumComments($numComments)
    {
        $this->numComments = $numComments;

        return $this;
    }

    /**
     * Get numComments
     *
     * @return integer 
     */
    public function getNumComments()
    {
        return $this->numComments;
    }

    /**
     * Set lastCommentAt
     *
     * @param \DateTime $lastCommentAt
     * @return Thread
     */
    public function setLastCommentAt($lastCommentAt)
    {
        $this->lastCommentAt = $lastCommentAt;

        return $this;
    }

    /**
     * Get lastCommentAt
     *
     * @return \DateTime 
     */
    public function getLastCommentAt()
    {
        return $this->lastCommentAt;
    }
}
