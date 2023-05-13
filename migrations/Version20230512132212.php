<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230512132212 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_thread (tag_id INT NOT NULL, thread_id INT NOT NULL, INDEX IDX_D86105AFBAD26311 (tag_id), INDEX IDX_D86105AFE2904019 (thread_id), PRIMARY KEY(tag_id, thread_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tag_thread ADD CONSTRAINT FK_D86105AFBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_thread ADD CONSTRAINT FK_D86105AFE2904019 FOREIGN KEY (thread_id) REFERENCES `thread` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE thread DROP tags');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tag_thread DROP FOREIGN KEY FK_D86105AFBAD26311');
        $this->addSql('ALTER TABLE tag_thread DROP FOREIGN KEY FK_D86105AFE2904019');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tag_thread');
        $this->addSql('ALTER TABLE `thread` ADD tags JSON NOT NULL');
    }
}
