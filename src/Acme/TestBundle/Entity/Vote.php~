<?php

namespace Acme\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vote
 */
class Vote
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $value;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \Acme\TestBundle\Entity\Rating
     */
    private $rating;

    /**
     * @var \Acme\TestBundle\Entity\Users
     */
    private $voter;


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
     * Set value
     *
     * @param integer $value
     * @return Vote
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return integer 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Vote
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set rating
     *
     * @param \Acme\TestBundle\Entity\Rating $rating
     * @return Vote
     */
    public function setRating(\Acme\TestBundle\Entity\Rating $rating = null)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return \Acme\TestBundle\Entity\Rating 
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set voter
     *
     * @param \Acme\TestBundle\Entity\Users $voter
     * @return Vote
     */
    public function setVoter(\Acme\TestBundle\Entity\Users $voter = null)
    {
        $this->voter = $voter;

        return $this;
    }

    /**
     * Get voter
     *
     * @return \Acme\TestBundle\Entity\Users 
     */
    public function getVoter()
    {
        return $this->voter;
    }
}
