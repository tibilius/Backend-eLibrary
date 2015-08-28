<?php

namespace Library\CatalogBundle\Repository;


use Library\CatalogBundle\Entity\ReadlistsBooks;
use Library\CatalogBundle\DBAL\Types\ReadlistEnumType;
use Library\CatalogBundle\Entity\Readlists;
use Library\CatalogBundle\Entity\ReadlistsBooksSort;
use Library\CatalogBundle\Entity\ReadlistsBooksSortItem;
use Library\UserBundle\Entity\User;

class ReadlistsBookRepository extends \Doctrine\ORM\EntityRepository
{

    public function clearReadlists(ReadlistsBooks $entity, User $user)
    {
        $types = array_map(
            function ($elem) {
                return "'{$elem}'";
            },
            array_diff(
                array_keys(ReadlistEnumType::getChoices()),
                [ReadlistEnumType::READED, ReadlistEnumType::MY_LIBRARY]
            )
        );
        return $this->getEntityManager()->getConnection()->query(
            'DELETE FROM readlists_books where id IN (
                SELECT rb.id
                FROM readlists_books rb
                INNER JOIN readlists  r on r.id = rb.readlist_id
                WHERE r.type IN (' . implode(',', $types) . ')
                    AND r.user_id = ' . $user->getId() . '
                    AND rb.id != ' . $entity->getId() . '
                    AND rb.book_id = ' . $entity->getBook()->getId() . '

            )'
        )->execute();
    }

    public function getLastPosition(Readlists $readlist, ReadlistsBooks $entity)
    {
        $lastItem = $this->getEntityManager()->createQueryBuilder()
            ->select('rb.position')
            ->from('CatalogBundle:ReadlistsBooks', 'rb')
            ->where('rb.readlist = :readlist_id')
            ->andWhere('rb.id != :id')
            ->orderBy('rb.position', 'desc')
            ->setMaxResults(1)
            ->setParameter('id', $entity->getId())
            ->setParameter('readlist_id', $readlist->getId())->getQuery()->getResult();
        return $lastItem ? intval($lastItem[0]['position']) + 1 : 0;
    }

    /**
     * @param ReadlistsBooksSort $entities
     * @param Readlists $readlists
     * @return bool
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function sort(ReadlistsBooksSort $entities, Readlists $readlists)
    {
        $em = $this->getEntityManager();
        $conn = $em->getConnection();
        $conn->beginTransaction();
        try {
            foreach ($entities->getReadlists() as $entity) {
                /**@var $entity ReadlistsBooksSortItem */
                $em->createQueryBuilder()
                    ->update('CatalogBundle:ReadlistsBooks', 'rb')
                    ->set('rb.position', $entity->getPosition())
                    ->where('rb.readlist = :readlist')
                    ->andWhere('rb.id = :id')
                    ->setParameter('readlist', $readlists->getId())
                    ->setParameter('id', $entity->getId())->getQuery()->execute();
            }
            $conn->commit();
            return true;
        } catch (\Exception $e) {
            $conn->rollBack();
            return false;
        }
    }


}
