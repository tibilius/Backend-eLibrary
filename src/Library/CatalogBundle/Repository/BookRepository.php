<?php

namespace Library\CatalogBundle\Repository;


class BookRepository extends \Doctrine\ORM\EntityRepository
{

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        if (isset($criteria['categories'])) {
            $result = $this->findByCategories($criteria['categories'], $orderBy, $limit, $offset);
        }elseif (isset($criteria['writers'])) {
            $result = $this->findByWriters($criteria['writers'], $orderBy, $limit, $offset);
        }else{
            $result = parent::findBy($criteria, $orderBy, $limit, $offset);
        }
        if (!count($result)) {
            return $result;
        }
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
            where books.id in (' . implode(',', array_keys($linked)). ')'
        )->fetchAll();
        foreach($dbResult as $row) {
            $linked[$row['id']]->setCommentCount((int)$row['cc'])->setReviewsCount((int)$row['rc']);
        }
        return $result;
    }

    public function findByCategories($categories, array $orderBy = null, $limit = null, $offset = null)
    {
        $query = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('b')
            ->from('CatalogBundle:Books', 'b')
            ->innerJoin('b.categories', 'c')
            ->where('c.id IN (:ids)')
            ->setParameter('ids', (array) $categories);
        foreach ($orderBy as $key => $order) {
            $query->orderBy('b.' . $key, strtoupper($order));
        }
        return $query
            ->setFirstResult((int)$offset)
            ->setMaxResults((int)$limit)
            ->getQuery()
            ->getResult();
    }

    public function findByWriters($writers, array $orderBy = null, $limit = null, $offset = null)
    {
        $query = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('b')
            ->from('CatalogBundle:Books', 'b')
            ->innerJoin('b.writers', 'w')
            ->where('w.id IN (:ids)')
            ->setParameter('ids', (array) $writers);
        foreach ($orderBy as $key => $order) {
            $query->orderBy('b.' . $key, strtoupper($order));
        }
        return $query
            ->setFirstResult((int)$offset)
            ->setMaxResults((int)$limit)
            ->getQuery()
            ->getResult();
    }

}