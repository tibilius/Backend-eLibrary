<?php

namespace Library\CatalogBundle\Repository;


class BookRepository extends \Doctrine\ORM\EntityRepository
{

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        if (isset($criteria['categories'])) {
            return $this->findByCategories($criteria['categories'], $orderBy, $limit, $offset);
        }
        if (isset($criteria['writers'])) {
            return $this->findByWriters($criteria['writers'], $orderBy, $limit, $offset);
        }
        return parent::findBy($criteria, $orderBy, $limit, $offset);
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