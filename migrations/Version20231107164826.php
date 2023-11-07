<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231107164826 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE work_session_task (work_session_id INT NOT NULL, task_id INT NOT NULL, INDEX IDX_967D89E57A5C410C (work_session_id), INDEX IDX_967D89E58DB60186 (task_id), PRIMARY KEY(work_session_id, task_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_session_tasks (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE work_session_task ADD CONSTRAINT FK_967D89E57A5C410C FOREIGN KEY (work_session_id) REFERENCES work_session (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE work_session_task ADD CONSTRAINT FK_967D89E58DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task ADD due_at DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE work_session_task DROP FOREIGN KEY FK_967D89E57A5C410C');
        $this->addSql('ALTER TABLE work_session_task DROP FOREIGN KEY FK_967D89E58DB60186');
        $this->addSql('DROP TABLE work_session_task');
        $this->addSql('DROP TABLE work_session_tasks');
        $this->addSql('ALTER TABLE task DROP due_at');
    }
}
