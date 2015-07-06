<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150706122520 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX books_owner_id_idx  ON books USING btree(rating_id)');

        $this->addSql('CREATE INDEX readlists_books_book_id_idx  ON readlists_books  USING btree (book_id)');
        $this->addSql('CREATE INDEX readlists_books_readlist_id_idx ON readlists_books USING btree (readlist_id)');
        $this->addSql('CREATE INDEX reviews_rating_id_idx  ON reviews USING btree (rating_id)');


    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
