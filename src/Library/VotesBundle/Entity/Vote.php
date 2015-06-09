<?php
namespace Library\VotesBundle\Entity;


use DCS\RatingBundle\Entity\Vote as BaseVote;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 */
class Vote extends BaseVote
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Library\VotesBundle\Entity\Rating", inversedBy="votes")
     * @ORM\JoinColumn(name="rating_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $rating;

    /**
     * @ORM\ManyToOne(targetEntity="Library\UserBundle\Entity\User")
     */
    protected $voter;
}