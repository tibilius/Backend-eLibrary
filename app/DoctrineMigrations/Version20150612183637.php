<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150612183637 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE books ADD COLUMN owner_id integer');
        $this->addSql('ALTER TABLE books  ADD CONSTRAINT fk_4a1b2a927e3c61f9 FOREIGN KEY (owner_id)
          REFERENCES users (id) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE CASCADE;');

        $this->addSql('ALTER TABLE users ADD COLUMN moderated boolean');
        $this->addSql('ALTER TABLE users ALTER COLUMN moderated SET NOT NULL');
        $this->addSql('ALTER TABLE users ALTER COLUMN moderated SET DEFAULT true;');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
    }
}
