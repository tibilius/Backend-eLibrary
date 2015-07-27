<?php


namespace Library\CommentBundle\Repository;


use Library\CommentBundle\Entity\Comment;

class CommentRepository extends \Doctrine\ORM\EntityRepository
{

    public function findLast($ownerId, $commentatorId = null, $limit = 10, $offset = null)
    {
        $owner = $this->getEntityManager()->getRepository('UserBundle:User')->find($ownerId);
        $sql = 'WITH threads as (
                select id, author_id, body, created_at, thread_id, ancestors,  created_at > \'' . $owner->getTimeReadedComments()->format('Y-m-d H:i:s') .'\' as new
                from comment c'
                . ($commentatorId ? ' where author_id=' . $commentatorId : '')
                . ' order by created_at desc
            )
            (
                select b.id as entity_id, \'books\' as type, t.*
                from threads t inner join books b on b.thread_id = t.thread_id
                 where b.owner_id = ' . $ownerId . '
                UNION
                select r.id as entity_id, \'reviews\' as type, t.*
                from threads t inner join reviews r on r.thread_id = t.thread_id
                where r.author_id = ' . $ownerId . '
            ) order by type limit ' . intval($limit) . ' offset ' . intval($offset);
        $entities = $this->getEntityManager()->getConnection()->query($sql)->fetchAll();
        $books = [];
        $reviews = [];
        $authors = [];
        foreach ($entities as $entity) {
            ${$entity['type']}[$entity['entity_id']][] = $entity;
            $authors[$entity['author_id']] = $entity['author_id'];
        }
        $dbUsers = $this->getEntityManager()->getRepository('UserBundle:User')->findBy(
            ['id' =>  array_values($authors)]
        );
        $dbBooks = $this->getEntityManager()->getRepository('CatalogBundle:Books')->findBy(
            ['id' => array_keys($books)]
        );
        $dbReviews = $this->getEntityManager()->getRepository('CatalogBundle:Reviews')->findBy(
            ['id' => array_keys($reviews)]
        );
        $mapUsers = [];
        foreach ($dbUsers as $user) {
            $mapUsers[$user->getId()] = &$user;
        }

        $setComment = function($comment) use (&$mapUsers) {
            $entity = new Comment();
            $entity->setId($comment['id']);
            $entity->setAuthor($mapUsers[$comment['author_id']]);
            $entity->setNew($comment['new']);
            $entity->setAncestors(explode('/', $comment['ancestors']));
            $entity->setCreatedAt(new \DateTime($comment['created_at']));
            $entity->setBody($comment['body']);
            return $entity;
        };
        foreach ($dbBooks as &$dbBook) {
            $comments = new \Doctrine\Common\Collections\ArrayCollection();
            foreach ($books[$dbBook->getId()] as $comment) {
                $comments->add($setComment($comment));
            }
            $dbBook->getThread()->setComments($comments);
        }
        foreach ($dbReviews as &$dbReview) {
            $comments = new \Doctrine\Common\Collections\ArrayCollection();
            foreach ($reviews[$dbReview->getId()] as $comment) {
                $comments->add($setComment($comment));
            }
            $dbReview->getThread()->setComments($comments);
        }
        return ['books' => $dbBooks, 'reviews' => $dbReviews];
    }
}