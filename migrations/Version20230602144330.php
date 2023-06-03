<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230602144330 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE content_bit (id INT AUTO_INCREMENT NOT NULL, thread_id INT NOT NULL, type VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_E2AEF562E2904019 (thread_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE thread_conversation (id INT AUTO_INCREMENT NOT NULL, content_bit_id INT NOT NULL, UNIQUE INDEX UNIQ_F9B1913975B7418B (content_bit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE thread_message (id INT AUTO_INCREMENT NOT NULL, conversation_id INT NOT NULL, content VARCHAR(255) NOT NULL, author_me TINYINT(1) NOT NULL, INDEX IDX_707D8369AC0396 (conversation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE content_bit ADD CONSTRAINT FK_E2AEF562E2904019 FOREIGN KEY (thread_id) REFERENCES `thread` (id)');
        $this->addSql('ALTER TABLE thread_conversation ADD CONSTRAINT FK_F9B1913975B7418B FOREIGN KEY (content_bit_id) REFERENCES content_bit (id)');
        $this->addSql('ALTER TABLE thread_message ADD CONSTRAINT FK_707D8369AC0396 FOREIGN KEY (conversation_id) REFERENCES thread_conversation (id)');
        $this->addSql('ALTER TABLE thread DROP content');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE content_bit DROP FOREIGN KEY FK_E2AEF562E2904019');
        $this->addSql('ALTER TABLE thread_conversation DROP FOREIGN KEY FK_F9B1913975B7418B');
        $this->addSql('ALTER TABLE thread_message DROP FOREIGN KEY FK_707D8369AC0396');
        $this->addSql('DROP TABLE content_bit');
        $this->addSql('DROP TABLE thread_conversation');
        $this->addSql('DROP TABLE thread_message');
        $this->addSql('ALTER TABLE `thread` ADD content JSON NOT NULL');
    }
}
