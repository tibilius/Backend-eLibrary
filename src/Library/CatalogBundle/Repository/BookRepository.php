<?php

namespace Library\CatalogBundle\Repository;


class BookRepository extends \Doctrine\ORM\EntityRepository
{

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        if (isset($criteria['categories'])) {
            return $this->findByCategories($criteria['categories'], $orderBy, $limit, $offset);
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

}