<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150609150149 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE books DROP CONSTRAINT fk_4a1b2a92a32efc6');
        $this->addSql('ALTER TABLE books
          ADD CONSTRAINT fk_4a1b2a92a32efc6 FOREIGN KEY (rating_id)
              REFERENCES rating (id) MATCH SIMPLE
              ON UPDATE NO ACTION ON DELETE SET NULL
        ');

        $this->addSql('ALTER TABLE books DROP CONSTRAINT fk_4a1b2a92e2904019');
        $this->addSql('ALTER TABLE books
          ADD CONSTRAINT fk_4a1b2a92e2904019 FOREIGN KEY (thread_id)
              REFERENCES thread (id) MATCH SIMPLE
              ON UPDATE NO ACTION ON DELETE SET NULL
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
