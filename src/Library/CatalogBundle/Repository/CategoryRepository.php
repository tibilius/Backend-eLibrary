<?php

namespace Library\CatalogBundle\Repository;


class CategoryRepository extends \Doctrine\ORM\EntityRepository
{

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $result = parent::findBy($criteria, $orderBy, $limit, $offset);
        $dbResult = $this->getEntityManager()->getConnection()->query(
            'select c.id, t2.bcount
            from categories c inner join
            (
                select bc.category_id, count(bc.book_id) bcount from books_categories bc
                inner join books b on b.id = bc.book_id
                where b.created >= now() - INTERVAL \'7 DAY\' group by bc.category_id
            ) as t2
                on t2.category_id = c.id'
            )->fetchAll();
        $lasts = [];
        foreach($dbResult as $row) {
            $lasts[$row['id']] = $row['bcount'];
        }
        foreach ($result as $entity) {
            $entity->setLast(
                isset($lasts[$entity->getId()])
                    ? $lasts[$entity->getId()]
                    : 0
            );
        }
        return $result;
    }



}