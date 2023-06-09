<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230608163305 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, thread_id INT NOT NULL, content LONGTEXT NOT NULL, upload_date DATETIME NOT NULL, INDEX IDX_9474526CF675F31B (author_id), INDEX IDX_9474526CE2904019 (thread_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE consent_request (id INT AUTO_INCREMENT NOT NULL, conversation_id INT NOT NULL, requester_id INT NOT NULL, recipient_id INT NOT NULL, thread_id INT NOT NULL, UNIQUE INDEX UNIQ_9007BFA39AC0396 (conversation_id), INDEX IDX_9007BFA3ED442CF4 (requester_id), INDEX IDX_9007BFA3E92F8F78 (recipient_id), UNIQUE INDEX UNIQ_9007BFA3E2904019 (thread_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE content_bit (id INT AUTO_INCREMENT NOT NULL, thread_id INT NOT NULL, type VARCHAR(255) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_E2AEF562E2904019 (thread_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conversation (id INT AUTO_INCREMENT NOT NULL, last_message_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_8A8E26E9BA0E79C3 (last_message_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, conversation_id INT NOT NULL, user_id INT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_B6BD307F9AC0396 (conversation_id), INDEX IDX_B6BD307FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participant (id INT AUTO_INCREMENT NOT NULL, conversation_id INT DEFAULT NULL, user_id INT NOT NULL, INDEX IDX_D79F6B119AC0396 (conversation_id), INDEX IDX_D79F6B11A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_thread (tag_id INT NOT NULL, thread_id INT NOT NULL, INDEX IDX_D86105AFBAD26311 (tag_id), INDEX IDX_D86105AFE2904019 (thread_id), PRIMARY KEY(tag_id, thread_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `thread` (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, upload_date DATETIME NOT NULL, closed TINYINT(1) NOT NULL, INDEX IDX_31204C83F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE thread_conversation (id INT AUTO_INCREMENT NOT NULL, content_bit_id INT DEFAULT NULL, helper VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_F9B1913975B7418B (content_bit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE thread_message (id INT AUTO_INCREMENT NOT NULL, conversation_id INT NOT NULL, content LONGTEXT NOT NULL, author_me TINYINT(1) NOT NULL, INDEX IDX_707D8369AC0396 (conversation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, role VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, bio VARCHAR(255) DEFAULT NULL, image VARCHAR(255) NOT NULL, registration_date DATE NOT NULL, dark_mode TINYINT(1) NOT NULL, main_column TINYINT(1) NOT NULL, chat_column TINYINT(1) NOT NULL, friend_column TINYINT(1) NOT NULL, chat_warning TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE friends (user_id INT NOT NULL, friend_user_id INT NOT NULL, INDEX IDX_21EE7069A76ED395 (user_id), INDEX IDX_21EE706993D1119E (friend_user_id), PRIMARY KEY(user_id, friend_user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CE2904019 FOREIGN KEY (thread_id) REFERENCES `thread` (id)');
        $this->addSql('ALTER TABLE consent_request ADD CONSTRAINT FK_9007BFA39AC0396 FOREIGN KEY (conversation_id) REFERENCES thread_conversation (id)');
        $this->addSql('ALTER TABLE consent_request ADD CONSTRAINT FK_9007BFA3ED442CF4 FOREIGN KEY (requester_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE consent_request ADD CONSTRAINT FK_9007BFA3E92F8F78 FOREIGN KEY (recipient_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE consent_request ADD CONSTRAINT FK_9007BFA3E2904019 FOREIGN KEY (thread_id) REFERENCES `thread` (id)');
        $this->addSql('ALTER TABLE content_bit ADD CONSTRAINT FK_E2AEF562E2904019 FOREIGN KEY (thread_id) REFERENCES `thread` (id)');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9BA0E79C3 FOREIGN KEY (last_message_id) REFERENCES message (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F9AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B119AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id)');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B11A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE tag_thread ADD CONSTRAINT FK_D86105AFBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_thread ADD CONSTRAINT FK_D86105AFE2904019 FOREIGN KEY (thread_id) REFERENCES `thread` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `thread` ADD CONSTRAINT FK_31204C83F675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE thread_conversation ADD CONSTRAINT FK_F9B1913975B7418B FOREIGN KEY (content_bit_id) REFERENCES content_bit (id)');
        $this->addSql('ALTER TABLE thread_message ADD CONSTRAINT FK_707D8369AC0396 FOREIGN KEY (conversation_id) REFERENCES thread_conversation (id)');
        $this->addSql('ALTER TABLE friends ADD CONSTRAINT FK_21EE7069A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE friends ADD CONSTRAINT FK_21EE706993D1119E FOREIGN KEY (friend_user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CF675F31B');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CE2904019');
        $this->addSql('ALTER TABLE consent_request DROP FOREIGN KEY FK_9007BFA39AC0396');
        $this->addSql('ALTER TABLE consent_request DROP FOREIGN KEY FK_9007BFA3ED442CF4');
        $this->addSql('ALTER TABLE consent_request DROP FOREIGN KEY FK_9007BFA3E92F8F78');
        $this->addSql('ALTER TABLE consent_request DROP FOREIGN KEY FK_9007BFA3E2904019');
        $this->addSql('ALTER TABLE content_bit DROP FOREIGN KEY FK_E2AEF562E2904019');
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E9BA0E79C3');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F9AC0396');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FA76ED395');
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B119AC0396');
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B11A76ED395');
        $this->addSql('ALTER TABLE tag_thread DROP FOREIGN KEY FK_D86105AFBAD26311');
        $this->addSql('ALTER TABLE tag_thread DROP FOREIGN KEY FK_D86105AFE2904019');
        $this->addSql('ALTER TABLE `thread` DROP FOREIGN KEY FK_31204C83F675F31B');
        $this->addSql('ALTER TABLE thread_conversation DROP FOREIGN KEY FK_F9B1913975B7418B');
        $this->addSql('ALTER TABLE thread_message DROP FOREIGN KEY FK_707D8369AC0396');
        $this->addSql('ALTER TABLE friends DROP FOREIGN KEY FK_21EE7069A76ED395');
        $this->addSql('ALTER TABLE friends DROP FOREIGN KEY FK_21EE706993D1119E');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE consent_request');
        $this->addSql('DROP TABLE content_bit');
        $this->addSql('DROP TABLE conversation');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE participant');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tag_thread');
        $this->addSql('DROP TABLE `thread`');
        $this->addSql('DROP TABLE thread_conversation');
        $this->addSql('DROP TABLE thread_message');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE friends');
    }
}
