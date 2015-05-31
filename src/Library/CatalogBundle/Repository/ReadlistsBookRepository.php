<?php

namespace Library\CatalogBundle\Repository;


use Library\CatalogBundle\DBAL\Types\ReadlistEnumType;
use Library\CatalogBundle\Entity\Books;
use Symfony\Component\DependencyInjection\Container;

class ReadlistsBookRepository extends \Doctrine\ORM\EntityRepository
{

    public function clearReadlists($entityId, $userId) {
        $types =  array_map(
            function($elem){return "'{$elem}'";},
            array_diff(array_keys(ReadlistEnumType::getChoices()), [ReadlistEnumType::READED])
        );
        return $this->getEntityManager()->getConnection()->query(
            'DELETE FROM readlists_books where id IN (
                SELECT rb.id
                FROM readlists_books rb
                INNER JOIN readlists  r on r.id = rb.readlist_id
                WHERE r.type IN (' . implode(',', $types). ')
                    AND r.user_id = ' . $userId . '
                    AND rb.id != ' .$entityId .  '

            )'
        )->execute();
    }


}
