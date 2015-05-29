<?php

namespace Library\CatalogBundle\Repository;


use Library\CatalogBundle\Entity\Books;
use Symfony\Component\DependencyInjection\Container;

class BookRepository extends \Doctrine\ORM\EntityRepository
{
    /***
     * @var Container
     */
    protected $container;

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        if (isset($criteria['categories'])) {
            $result = $this->_findByCategories($criteria['categories'], $orderBy, $limit, $offset);
        } elseif (isset($criteria['writers'])) {
            $result = $this->_findByWriters($criteria['writers'], $orderBy, $limit, $offset);
        } else {
            $result = parent::findBy($criteria, $orderBy, $limit, $offset);
        }
        if (!count($result)) {
            return $result;
        }
        $this->_mergeReviewsAndCommentsCount($result);
        $this->_mergeUserReadlistsIds($result);
        return $result;
    }

    protected function _findByCategories($categories, array $orderBy = null, $limit = null, $offset = null)
    {
        $query = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('b')
            ->from('CatalogBundle:Books', 'b')
            ->innerJoin('b.categories', 'c')
            ->where('c.id IN (:ids)')
            ->setParameter('ids', (array)$categories);
        foreach ($orderBy as $key => $order) {
            $query->orderBy('b.' . $key, strtoupper($order));
        }
        return $query
            ->setFirstResult((int)$offset)
            ->setMaxResults((int)$limit)
            ->getQuery()
            ->getResult();
    }

    protected function _findByWriters($writers, array $orderBy = null, $limit = null, $offset = null)
    {
        $query = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('b')
            ->from('CatalogBundle:Books', 'b')
            ->innerJoin('b.writers', 'w')
            ->where('w.id IN (:ids)')
            ->setParameter('ids', (array)$writers);
        foreach ($orderBy as $key => $order) {
            $query->orderBy('b.' . $key, strtoupper($order));
        }
        return $query
            ->setFirstResult((int)$offset)
            ->setMaxResults((int)$limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $result
     * @throws \Doctrine\DBAL\DBALException
     * @return void
     */
    protected function _mergeReviewsAndCommentsCount(&$result)
    {
        $linked = [];
        foreach ($result as &$entity) {
            $linked[$entity->getId()] = $entity;
        }
        $dbResult = $this->getEntityManager()->getConnection()->query('
            select books.id, rcount.rc, ccount.cc from
            books     left join
              (select b.id, count(reviews.id) as rc from books b inner join reviews on b.id = reviews.book_id GROUP BY b.id) as rcount
                on rcount.id = books.id
            left join
              (select b.id, count(c.id) as cc from books b, thread t, "comment" c WHERE c.thread_id = t.id AND b.thread_id = t.id AND c.thread_id = t.id GROUP BY b.id) as ccount
                on ccount.id = books.id
            where books.id in (' . implode(',', array_keys($linked)) . ')'
        )->fetchAll();
        foreach ($dbResult as $row) {
            $linked[$row['id']]->setCommentCount((int)$row['cc'])->setReviewsCount((int)$row['rc']);
        }
    }

    /**
     * @param Books[] $result
     * @throws \Doctrine\DBAL\DBALException
     * @return void
     */
    protected function _mergeUserReadlistsIds(&$result)
    {
        if (!$token = $this->getContainer()->get('security.context')->getToken()) {
            return;
        }
        $user = $token->getUser();
        $linked = [];
        foreach ($result as &$entity) {
            $entity->setUserReadlistsIds([]);
            $linked[$entity->getId()] = $entity;
        }
        $dbResult = $this->getEntityManager()->getConnection()->query('
            select b.id as id, r.id as rid, rb.id as rbid from
            books b
            inner join readlists_books rb  on rb.book_id = b.id
            inner join readlists r on r.id = rb.readlist_id
            where b.id in (' . implode(',', array_keys($linked)) . ')
            AND r.user_id = ' . $user->getId()
        )->fetchAll();
        foreach ($dbResult as $row) {
            $linked[$row['id']]->addUserReadlistsId($row['rid'], $row['rbid']);
        }
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param Container $container
     * @return BookRepository;
     */
    public function setContainer($container)
    {
        $this->container = $container;
        return $this;
    }

    public function findByQuery($query, $limit = null, $offset = null)
    {
        $db = $this->getEntityManager()->createQueryBuilder();
        $query = str_replace(' ', ' | ', $query);
        $dQuery = $db
            ->select('b')
            ->from('CatalogBundle:Books', 'b')
            ->innerJoin('b.categories', 'c')
            ->innerJoin('b.writers', 'w')
            ->where('TSQUERY(TOTSVECTOR(b.name), :query) = true')
            ->orWhere('TSQUERY(TOTSVECTOR(b.isbn), :query) = true')
            ->orWhere('TSQUERY(TOTSVECTOR(c.name), :query) = true')
            ->orWhere('TSQUERY(TOTSVECTOR(w.firstName), :query) = true')
            ->orWhere('TSQUERY(TOTSVECTOR(w.middleName), :query) = true')
            ->orWhere('TSQUERY(TOTSVECTOR(w.lastName), :query) = true')
            ->setParameter('query', $query)
            ->setFirstResult((int)$offset)
            ->setMaxResults((int)$limit)
            ->getQuery();
        return $dQuery->getResult();
    }

}
