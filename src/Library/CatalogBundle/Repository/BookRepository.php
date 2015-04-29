<?php

namespace Library\CatalogBundle\Repository;


class BookRepository extends \Doctrine\ORM\EntityRepository
{

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) {
        return parent::findBy($criteria, $orderBy,  $limit, $offset);
    }


}