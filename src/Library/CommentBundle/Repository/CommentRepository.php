<?php


namespace Library\CommentBundle\Repository;


use Library\CommentBundle\Entity\Comment;
use Library\CommentBundle\Entity\Thread;

class CommentRepository extends \Doctrine\ORM\EntityRepository
{

    public function findLast($ownerId, $commentatorId = null, $limit = 10, $offset = null)
    {
        $owner = $this->getEntityManager()->getRepository('UserBundle:User')->find($ownerId);
        $sql = 'WITH threads as (
                select id, author_id, body, created_at, thread_id, ancestors,  created_at > \'' . $owner->getTimeReadedComments()->format('Y-m-d H:i:s') .'\' as new
                from comment c
                where ancestors = \'\''
                . ($commentatorId ? ' and author_id=' . $commentatorId : '')
                . ' order by created_at desc
            )
            (
                (select b.id as entity_id, \'books\' as type, t.*
                from threads t inner join books b on b.thread_id = t.thread_id
                where b.owner_id = ' . $ownerId . '
                limit ' . intval($limit) . ' offset ' . intval($offset) . ')
                UNION
                (select r.id as entity_id, \'reviews\' as type, t.*
                from threads t inner join reviews r on r.thread_id = t.thread_id
                where r.author_id = ' . $ownerId . '
                limit ' . intval($limit) . ' offset ' . intval($offset) . ')
            ) order by type';
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
        foreach ($dbUsers as &$user) {
            $mapUsers[$user->getId()] = $user;
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
        return ['books' => $dbBooks, 'reviews' => $dbReviews, 'comments' => $this->getCommentAnswers($ownerId, $commentatorId, $limit, $offset)];
    }

    public function findLastAnswerCount($ownerId, $commentatorId = null)
    {
        $owner = $this->getEntityManager()->getRepository('UserBundle:User')->find($ownerId);
        $sql = 'WITH threads as (
                select id, author_id, body, created_at, thread_id, ancestors
                from comment c
                where ancestors = \'\'
                AND created_at > \'' . $owner->getTimeReadedComments()->format('Y-m-d H:i:s') .'\''
                . ($commentatorId ? ' and author_id=' . $commentatorId : '')
                . ' order by created_at desc
            )
            (
                select COUNT(b.id) as count, \'books\' as type
                from threads t inner join books b on b.thread_id = t.thread_id
                 where b.owner_id = ' . $ownerId . '
                UNION
                select COUNT(r.id) as count, \'reviews\' as type
                from threads t inner join reviews r on r.thread_id = t.thread_id
                where r.author_id = ' . $ownerId . '
                UNION
                SELECT count(id) as count, \'comments\' as type
                FROM comment WHERE ancestors IN (
                    SELECT CASE ancestors <> \'\' WHEN TRUE THEN ancestors::text || \'/\' || id::text ELSE id::text END
	                FROM comment WHERE author_id = ' . $owner->getId() . '
                )
                AND author_id <>  ' . $owner->getId() . '
                AND created_at > \''.  $owner->getTimeReadedComments()->format('Y-m-d H:i:s') .'\'
            )';
        $result =  $this->getEntityManager()->getConnection()->query($sql)->fetchAll();
        $count = [
            'books' => 0,
            'reviews' => 0,
            'comments' => 0,
        ];
        foreach($result as $row) {
            $count[$row['type']] = $row['count'];
        }
        return $count;
    }


    public function getCommentAnswers($ownerId, $commentatorId = null, $limit = 10, $offset = null)
    {
        $owner = $this->getEntityManager()->getRepository('UserBundle:User')->find($ownerId);
        $comments = $this->getEntityManager()->getConnection()->query(
            'SELECT comment.*, created_at > \''.  $owner->getTimeReadedComments()->format('Y-m-d H:i:s') .'\' as new FROM comment WHERE ancestors IN (
	            SELECT CASE ancestors <> \'\' WHEN TRUE THEN ancestors::text || \'/\' || id::text ELSE id::text END
	            FROM comment WHERE author_id = ' . $owner->getId() . '
            )
            AND author_id <>  ' . $ownerId .'
            order by created_at desc
            limit ' . intval($limit) . ' offset ' . intval($offset)
        )->fetchAll();
        $authors = [];
        $threads = [];
        foreach ($comments as $entity) {
            $authors[$entity['author_id']] = $entity['author_id'];
            $threads[$entity['thread_id']] = $entity['thread_id'];
        }
        $dbUsers = $this->getEntityManager()->getRepository('UserBundle:User')->findBy(
            ['id' =>  array_values($authors)]
        );
        $dbThreads = $this->getEntityManager()->getRepository('LibraryCommentBundle:Thread') ->findBy([
            'id' => array_keys($threads)
        ]);
        foreach($dbThreads as &$thread) {
            $thread->setComments(new \Doctrine\Common\Collections\ArrayCollection());
            $threads[$thread->getId()] = $thread;
        }
        $mapUsers = [];
        foreach ($dbUsers as &$user) {
            $mapUsers[$user->getId()] = $user;
        }
//        $result = new \Doctrine\Common\Collections\ArrayCollection();
        foreach($comments as $comment) {
            $entity = new Comment();
            $entity->setId($comment['id']);
            $entity->setAuthor($mapUsers[$comment['author_id']]);
            $entity->setNew($comment['new']);
            $entity->setAncestors(explode('/', $comment['ancestors']));
            $entity->setCreatedAt(new \DateTime($comment['created_at']));
            $entity->setBody($comment['body']);
            $threads[$comment['thread_id']]->addComment($entity);
        }
        return array_values($threads);
    }

}