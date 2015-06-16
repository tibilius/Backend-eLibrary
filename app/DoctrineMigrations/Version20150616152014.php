<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150616152014 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE reviews DROP CONSTRAINT FK_6970EB0F16A2B381');
        $this->addSql('ALTER TABLE reviews DROP CONSTRAINT FK_6970EB0FE2904019');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0F16A2B381 FOREIGN KEY (book_id) REFERENCES books (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0FE2904019 FOREIGN KEY (thread_id) REFERENCES thread (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE books DROP CONSTRAINT FK_4A1B2A92A32EFC6');
        $this->addSql('ALTER TABLE books DROP CONSTRAINT FK_4A1B2A92E2904019');
        $this->addSql('ALTER TABLE books ADD CONSTRAINT FK_4A1B2A92A32EFC6 FOREIGN KEY (rating_id) REFERENCES rating (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE books ADD CONSTRAINT FK_4A1B2A92E2904019 FOREIGN KEY (thread_id) REFERENCES thread (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE books_writers DROP CONSTRAINT FK_115D0B4C16A2B381');
        $this->addSql('ALTER TABLE books_writers DROP CONSTRAINT FK_115D0B4C1BC7E6B6');
        $this->addSql('ALTER TABLE books_writers ADD CONSTRAINT FK_115D0B4C16A2B381 FOREIGN KEY (book_id) REFERENCES books (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE books_writers ADD CONSTRAINT FK_115D0B4C1BC7E6B6 FOREIGN KEY (writer_id) REFERENCES writers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE books_categories DROP CONSTRAINT FK_16746F1512469DE2');
        $this->addSql('ALTER TABLE books_categories DROP CONSTRAINT FK_16746F1516A2B381');
        $this->addSql('ALTER TABLE books_categories ADD CONSTRAINT FK_16746F1512469DE2 FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE books_categories ADD CONSTRAINT FK_16746F1516A2B381 FOREIGN KEY (book_id) REFERENCES books (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT fk_5bc96bf0f675f31b');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_5BC96BF0E2904019');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_5BC96BF0A76ED395 FOREIGN KEY (author_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_5BC96BF0E2904019 FOREIGN KEY (thread_id) REFERENCES thread (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_5BC96BF0A76ED395 ON comment (author_id)');
        $this->addSql('ALTER TABLE vote DROP CONSTRAINT FK_FA222A5AA32EFC6');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_FA222A5AA32EFC6 FOREIGN KEY (rating_id) REFERENCES rating (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE readlists ALTER type TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE users ALTER created SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE users ALTER updated SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE reviews DROP CONSTRAINT fk_6970eb0f16a2b381');
        $this->addSql('ALTER TABLE reviews DROP CONSTRAINT fk_6970eb0fe2904019');
        $this->addSql('ALTER TABLE reviews ALTER title DROP NOT NULL');
        $this->addSql('ALTER TABLE reviews ALTER created DROP NOT NULL');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT fk_6970eb0f16a2b381 FOREIGN KEY (book_id) REFERENCES books (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT fk_6970eb0fe2904019 FOREIGN KEY (thread_id) REFERENCES thread (id)');
        $this->addSql('CREATE SEQUENCE readlists_books_id_seq');
        $this->addSql('SELECT setval(\'readlists_books_id_seq\', (SELECT MAX(id) FROM readlists_books))');
        $this->addSql('ALTER TABLE readlists_books ALTER id SET DEFAULT nextval(\'readlists_books_id_seq\')');
        $this->addSql('ALTER TABLE readlists_books ALTER book_id SET NOT NULL');
        $this->addSql('ALTER TABLE readlists_books ALTER readlist_id SET NOT NULL');
        $this->addSql('ALTER TABLE readlists_books ALTER position DROP NOT NULL');
        $this->addSql('ALTER TABLE Vote DROP CONSTRAINT fk_fa222a5aa32efc6');
        $this->addSql('ALTER TABLE Vote ADD CONSTRAINT fk_fa222a5aa32efc6 FOREIGN KEY (rating_id) REFERENCES rating (id)');
        $this->addSql('ALTER TABLE books_writers DROP CONSTRAINT fk_115d0b4c16a2b381');
        $this->addSql('ALTER TABLE books_writers DROP CONSTRAINT fk_115d0b4c1bc7e6b6');
        $this->addSql('ALTER TABLE books_writers ADD CONSTRAINT fk_115d0b4c16a2b381 FOREIGN KEY (book_id) REFERENCES books (id)');
        $this->addSql('ALTER TABLE books_writers ADD CONSTRAINT fk_115d0b4c1bc7e6b6 FOREIGN KEY (writer_id) REFERENCES writers (id)');
        $this->addSql('ALTER TABLE Comment DROP CONSTRAINT FK_5BC96BF0A76ED395');
        $this->addSql('ALTER TABLE Comment DROP CONSTRAINT fk_5bc96bf0e2904019');
        $this->addSql('DROP INDEX IDX_5BC96BF0A76ED395');
        $this->addSql('ALTER TABLE Comment RENAME COLUMN user_id TO author_id');
        $this->addSql('ALTER TABLE Comment ADD CONSTRAINT fk_5bc96bf0f675f31b FOREIGN KEY (author_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Comment ADD CONSTRAINT fk_5bc96bf0e2904019 FOREIGN KEY (thread_id) REFERENCES thread (id)');
        $this->addSql('CREATE INDEX idx_5bc96bf0f675f31b ON Comment (author_id)');
        $this->addSql('ALTER TABLE books_categories DROP CONSTRAINT fk_16746f1516a2b381');
        $this->addSql('ALTER TABLE books_categories DROP CONSTRAINT fk_16746f1512469de2');
        $this->addSql('ALTER TABLE books_categories ADD CONSTRAINT fk_16746f1516a2b381 FOREIGN KEY (book_id) REFERENCES books (id)');
        $this->addSql('ALTER TABLE books_categories ADD CONSTRAINT fk_16746f1512469de2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE books DROP CONSTRAINT fk_4a1b2a92e2904019');
        $this->addSql('ALTER TABLE books DROP CONSTRAINT fk_4a1b2a92a32efc6');
        $this->addSql('ALTER TABLE books ADD CONSTRAINT fk_4a1b2a92e2904019 FOREIGN KEY (thread_id) REFERENCES thread (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE books ADD CONSTRAINT fk_4a1b2a92a32efc6 FOREIGN KEY (rating_id) REFERENCES rating (id) ON DELETE SET NULL');
    }
}
