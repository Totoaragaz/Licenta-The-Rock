<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230430125910 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE thread (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, content JSON NOT NULL, upload_date DATETIME NOT NULL, closed TINYINT(1) NOT NULL, tags JSON NOT NULL, INDEX IDX_31204C83F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE thread ADD CONSTRAINT FK_31204C83F675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user ADD main_column TINYINT(1) NOT NULL, ADD chat_column TINYINT(1) NOT NULL, ADD friend_column TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE thread DROP FOREIGN KEY FK_31204C83F675F31B');
        $this->addSql('DROP TABLE thread');
        $this->addSql('ALTER TABLE `user` DROP main_column, DROP chat_column, DROP friend_column');
    }
}
