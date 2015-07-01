<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Library\CatalogBundle\DBAL\Types\ReadlistEnumType;
use Library\CatalogBundle\Entity\Readlists;
use Library\CatalogBundle\Entity\ReadlistsBooks;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150701120128 extends AbstractMigration implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('
        INSERT INTO readlists_books (book_id, readlist_id, plan, fact, position)
	        SELECT b.id as book_id, r.id as readlist_id, null as plan, null as fact, null as position
	        FROM books b
	        INNER JOIN readlists r
	        ON r.user_id=b.owner_id and r.type=\'my_library\'
	          where (r.id, b.id) not in (select readlist_id, book_id from readlists_books)'
        );

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
