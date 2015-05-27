<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150527171417 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('DROP TABLE readlists_books');
        $this->addSql('
        CREATE TABLE readlists_books
        (
          book_id integer NOT NULL,
          readlist_id integer NOT NULL,
          fact integer,
          plan integer,
          id serial NOT NULL,
          CONSTRAINT pk PRIMARY KEY (id),
          CONSTRAINT fk_ea9e745816a2b381 FOREIGN KEY (book_id)
              REFERENCES books (id) MATCH SIMPLE
              ON UPDATE NO ACTION ON DELETE CASCADE,
          CONSTRAINT fk_ea9e7458457c67b9 FOREIGN KEY (readlist_id)
              REFERENCES readlists (id) MATCH SIMPLE
              ON UPDATE NO ACTION ON DELETE CASCADE,
           CONSTRAINT readlists_books_book_id_readlist_id_key UNIQUE (book_id, readlist_id)

        )
        ');


    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
