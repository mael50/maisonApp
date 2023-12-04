<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231115152643 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recurring_tasks (id INT AUTO_INCREMENT NOT NULL, task_id INT DEFAULT NULL, user_id INT DEFAULT NULL, recurrency VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_3A47A8C88DB60186 (task_id), INDEX IDX_3A47A8C8A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recurring_tasks ADD CONSTRAINT FK_3A47A8C88DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
        $this->addSql('ALTER TABLE recurring_tasks ADD CONSTRAINT FK_3A47A8C8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recurring_tasks DROP FOREIGN KEY FK_3A47A8C88DB60186');
        $this->addSql('ALTER TABLE recurring_tasks DROP FOREIGN KEY FK_3A47A8C8A76ED395');
        $this->addSql('DROP TABLE recurring_tasks');
    }
}
