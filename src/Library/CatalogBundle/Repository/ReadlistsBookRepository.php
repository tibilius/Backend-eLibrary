<?php

namespace Library\CatalogBundle\Repository;


use Acme\TestBundle\Entity\ReadlistsBooks;
use Library\CatalogBundle\DBAL\Types\ReadlistEnumType;
use Library\CatalogBundle\Entity\Books;
use Library\CatalogBundle\Entity\Readlists;
use Library\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\Container;

class ReadlistsBookRepository extends \Doctrine\ORM\EntityRepository
{

    public function clearReadlists(ReadlistsBooks $entity, User $user) {
        $types =  array_map(
            function($elem){ return "'{$elem}'"; },
            array_diff(array_keys(ReadlistEnumType::getChoices()), [ReadlistEnumType::READED])
        );
        return $this->getEntityManager()->getConnection()->query(
            'DELETE FROM readlists_books where id IN (
                SELECT rb.id
                FROM readlists_books rb
                INNER JOIN readlists  r on r.id = rb.readlist_id
                WHERE r.type IN (' . implode(',', $types). ')
                    AND r.user_id = ' . $user->getId() . '
                    AND rb.id != ' .$entity->getId() .  '
                    AND rb.book_id = ' . $entity->getBook()->getId() . '

            )'
        )->execute();
    }

    public function getLastPosition(Readlists $readlist) {
        $lastItem = $this->getEntityManager()->createQueryBuilder()
            ->select('rb.position')
            ->from('CatalogBundle:ReadlistsBooks', 'rb')
            ->where('rb.readlist = :readlist_id')
            ->orderBy('rb.position', 'desc')
            ->setMaxResults(1)
            ->setParameter('readlist_id', $readlist->getId())->getQuery()->getResult();
        return $lastItem ? intval($lastItem[0]['position']) + 1 : 0;
    }


}
