<?php


namespace Library\CatalogBundle\Repository;


use Library\CatalogBundle\Entity\Books;
use Library\UserBundle\Entity\User;
use Library\CatalogBundle\DBAL\Types\ReadlistEnumType;

class ReadlistsRepository extends \Doctrine\ORM\EntityRepository
{
    public function isReaded(User $user, Books $book)
    {
        return (bool)$this->findBy([
            'user' => $user,
            'type' => ReadlistEnumType::READED,
            'book' => $book,
        ]);
    }

    public function isPaused(User $user, Books $book)
    {
        return (bool)$this->findBy([
            'user' => $user,
            'type' => ReadlistEnumType::PAUSED,
            'book' => $book,
        ]);
    }

    public function isInRead(User $user, Books $book)
    {
        return (bool)$this->findBy([
            'user' => $user,
            'type' => ReadlistEnumType::IN_READ,
            'book' => $book,
        ]);
    }
}