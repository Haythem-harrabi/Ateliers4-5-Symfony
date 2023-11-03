<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231102120904 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE author ADD nb_books INT NOT NULL');
        $this->addSql('ALTER TABLE book ADD author INT DEFAULT NULL');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331BDAFD8C8 FOREIGN KEY (author) REFERENCES author (id)');
        $this->addSql('CREATE INDEX IDX_CBE5A331BDAFD8C8 ON book (author)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE author DROP nb_books');
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A331BDAFD8C8');
        $this->addSql('DROP INDEX IDX_CBE5A331BDAFD8C8 ON book');
        $this->addSql('ALTER TABLE book DROP author');
    }
}
