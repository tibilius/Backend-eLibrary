<?php


namespace Library\CatalogBundle\Repository;


use Library\CatalogBundle\Entity\Books;
use Library\UserBundle\Entity\User;
use Library\CatalogBundle\DBAL\Types\ReadlistEnumType;

class ReadlistsRepository extends \Doctrine\ORM\EntityRepository
{

    public function isReaded(User $user, Books $book)
    {
        $query = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('r')
            ->from('CatalogBundle:Readlists', 'r')
            ->innerJoin('r.books', 'books')
            ->innerJoin('r.user', 'u')
            ->where('books.id = :bid AND u.id = :uid AND r.type = :type')
            ->setParameter('type', ReadlistEnumType::READED)
            ->setParameter('bid', $book->getId())
            ->setParameter('uid', $user->getId());

        return (bool)$query
            ->getQuery()
            ->getResult();
    }

    public function isPaused(User $user, Books $book)
    {
        return (bool)$this->findBy([
            'user' => $user,
            'type' => ReadlistEnumType::PAUSED,
            'books' => $book,
        ]);
    }

    public function isInRead(User $user, Books $book)
    {
        return (bool)$this->findBy([
            'user' => $user,
            'type' => ReadlistEnumType::IN_READ,
            'books' => $book,
        ]);
    }
}