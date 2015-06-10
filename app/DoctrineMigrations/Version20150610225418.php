<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150610225418 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE books DROP CONSTRAINT fk_4a1b2a92a32efc6');
        $this->addSql('ALTER TABLE books ADD CONSTRAINT fk_4a1b2a92a32efc6 FOREIGN KEY (rating_id)
              REFERENCES rating (id) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE SET NULL
        ');

        $this->addSql('ALTER TABLE books DROP CONSTRAINT fk_4a1b2a92e2904019');
        $this->addSql('ALTER TABLE books ADD CONSTRAINT fk_4a1b2a92e2904019 FOREIGN KEY (thread_id)
              REFERENCES thread (id) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE SET NULL
        ');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT fk_5bc96bf0f675f31b');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT fk_5bc96bf0f675f31b
            FOREIGN KEY (author_id)  REFERENCES users (id) MATCH SIMPLE
            ON UPDATE NO ACTION ON DELETE CASCADE
        ');

        $this->addSql('ALTER TABLE reviews DROP CONSTRAINT fk_6970eb0ff675f31b');
        $this->addSql('ALTER TABLE reviews  ADD CONSTRAINT fk_6970eb0ff675f31b
            FOREIGN KEY (author_id)  REFERENCES users (id) MATCH SIMPLE
                ON UPDATE NO ACTION ON DELETE CASCADE
        ');

        $this->addSql(' ALTER TABLE reviews DROP CONSTRAINT fk_6970eb0f16a2b381');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT fk_6970eb0f16a2b381 FOREIGN KEY (book_id)
            REFERENCES books (id) MATCH SIMPLE      ON UPDATE NO ACTION ON DELETE SET NULL');

        $this->addSql('ALTER TABLE readlists DROP CONSTRAINT fk_d0f73387a76ed395');
        $this->addSql('ALTER TABLE readlists  ADD CONSTRAINT fk_d0f73387a76ed395 FOREIGN KEY (user_id)
            REFERENCES users (id) MATCH SIMPLE   ON UPDATE NO ACTION ON DELETE CASCADE');

        $this->addSql('ALTER TABLE vote DROP CONSTRAINT fk_fa222a5aebb4b8ad');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT fk_fa222a5aebb4b8ad FOREIGN KEY (voter_id)
            REFERENCES users (id) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE CASCADE');



    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
