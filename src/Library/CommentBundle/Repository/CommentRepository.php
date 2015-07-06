<?php


namespace Library\CommentBundle\Repository;


use Library\CommentBundle\Entity\Comment;

class CommentRepository extends \Doctrine\ORM\EntityRepository
{

    public function findLast($limit = 10, $offset = null)
    {
        $entities = $this->getEntityManager()->getConnection()->query(
            'WITH threads as (
                select id, author_id, body, created_at, thread_id
                from comment c
                order by created_at desc
                limit ' . intval($limit) . ' offset ' . intval($offset) . '
            )
            (
                select b.id as entity_id, \'books\' as type, t.*
                from threads t inner join books b on b.thread_id = t.thread_id
                UNION
                select r.id as entity_id, \'reviews\' as type, t.*
                from threads t inner join reviews r on r.thread_id = t.thread_id
            ) order by type'
        )->fetchAll();
        $books  =  [];
        $reviews = [];
        $authors  = [];
        foreach ($entities as $entity) {
            ${$entity['type']}[$entity['entity_id']][] = $entity;
            $authors[] = $entity['author_id'];
        }
        $dbUsers = $this->getEntityManager()->getRepository('UserBundle:User')->findBy(
            ['id' => $authors]
        );
        $dbBooks = $this->getEntityManager()->getRepository('CatalogBundle:Books')->findBy(
            ['id'=> array_keys($books)]
        );
        $dbReviews = $this->getEntityManager()->getRepository('CatalogBundle:Reviews')->findBy(
            ['id'=> array_keys($reviews)]
        );
        $mapUsers = [];
        foreach ($dbUsers as $user) {
            $mapUsers[$user->getId()] = &$user;
        }
        foreach($dbBooks as &$dbBook) {
            $comments = new \Doctrine\Common\Collections\ArrayCollection();
            foreach ($books[$dbBook->getId()] as $comment) {
                $entity = new Comment();
                $entity->setAuthor($mapUsers[$comment['author_id']]);
                $entity->setCreatedAt(new \DateTime($comment['created_at']));
                $entity->setBody($comment['body']);
                $comments->add($entity);
            }
            $dbBook->getThread()->setComments($comments);
        }
        foreach($dbReviews as &$dbReview) {
            $comments = new \Doctrine\Common\Collections\ArrayCollection();
            foreach ($reviews[$dbReview->getId()] as $comment) {
                $entity = new Comment();
                $entity->setAuthor($mapUsers[$comment['author_id']]);
                $entity->setCreatedAt(new \DateTime($comment['created_at']));
                $entity->setBody($comment['body']);
                $comments->add($entity);
            }
            $dbReview->getThread()->setComments($comments);
        }
        return ['books' => $dbBooks, 'reviews' => $dbReviews];
    }
}