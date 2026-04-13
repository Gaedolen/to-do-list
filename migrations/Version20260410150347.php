<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260410150347 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `column` (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, workspace_id INT NOT NULL, INDEX IDX_7D53877E82D40A1F (workspace_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, is_important TINYINT NOT NULL, column_id INT NOT NULL, INDEX IDX_527EDB25BE8E8ED5 (column_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE workspace (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(150) NOT NULL, user_id INT NOT NULL, INDEX IDX_8D940019A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE `column` ADD CONSTRAINT FK_7D53877E82D40A1F FOREIGN KEY (workspace_id) REFERENCES workspace (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25BE8E8ED5 FOREIGN KEY (column_id) REFERENCES `column` (id)');
        $this->addSql('ALTER TABLE workspace ADD CONSTRAINT FK_8D940019A76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `column` DROP FOREIGN KEY FK_7D53877E82D40A1F');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25BE8E8ED5');
        $this->addSql('ALTER TABLE workspace DROP FOREIGN KEY FK_8D940019A76ED395');
        $this->addSql('DROP TABLE `column`');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE workspace');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
