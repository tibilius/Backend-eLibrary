<?php

namespace Library\CatalogBundle\Repository;


class CategoryRepository extends \Doctrine\ORM\EntityRepository
{

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {

        $result = parent::findBy($criteria, $orderBy, $limit, $offset);
        if (!count($result)) {
            return $result;
        }
        $linked = [];
        foreach ($result as &$entity) {
            $linked[$entity->getId()] = $entity;
        }
        $sql ='select c.id, t2.bcount, t3.ballcount
            from categories c
            LEFT JOIN
            (
                select bc.category_id, count(bc.book_id) bcount from books_categories bc
                inner join books b on b.id = bc.book_id
                where b.created >= now() - INTERVAL \'7 DAY\' group by bc.category_id
            ) as t2 ON t2.category_id = c.id
            LEFT JOIN
            (
              select bc.category_id, count(bc.book_id) ballcount from books_categories bc  group by bc.category_id
            ) as t3 ON t3.category_id = c.id
            where
                c.id IN (' . implode(',', array_keys($linked)) . ')';
        $dbResult = $this->getEntityManager()->getConnection()->query($sql)->fetchAll();
        foreach($dbResult as $row) {
            $linked[$row['id']]->setLast((int)$row['bcount'])->setItems((int)$row['ballcount']);
        }

        return $result;
    }



}