<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150524144832 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE readlist_books RENAME TO readlists_books');
//        $this->addSql('ALTER TABLE readlist_books RENAME book_id  TO books_id');
//        $this->addSql('ALTER TABLE readlist_books RENAME TO readlists_books');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE readlists_books RENAME TO readlist_books');

    }
}
