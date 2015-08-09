<?php

namespace Library\CommentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\CommentBundle\Entity\Thread as BaseThread;

/**
 * @ORM\Entity
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 */
class Thread extends BaseThread
{
    /**
     * @var string $id
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $comments;

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $comments
     * @return Thread
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
        return $this;
    }

    /**
     * @param \Library\CommentBundle\Entity\Comment $comment
     * @return Thread
     */
    public function addComment($comment)
    {
        $this->comments->add($comment);
        return $this;
    }

    /**
     * @param \Library\CommentBundle\Entity\Comment $comment
     * @return Thread
     */
    public function removeComment($comment)
    {
        $this->comments->removeElement($comment);
        return $this;
    }




}