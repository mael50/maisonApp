<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231107144238 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, assigned_user_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, is_done TINYINT(1) NOT NULL, INDEX IDX_527EDB25ADF66B1A (assigned_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_session (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, INDEX IDX_58F6DF6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_session_task (work_session_id INT NOT NULL, task_id INT NOT NULL, INDEX IDX_967D89E57A5C410C (work_session_id), INDEX IDX_967D89E58DB60186 (task_id), PRIMARY KEY(work_session_id, task_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25ADF66B1A FOREIGN KEY (assigned_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE work_session ADD CONSTRAINT FK_58F6DF6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE work_session_task ADD CONSTRAINT FK_967D89E57A5C410C FOREIGN KEY (work_session_id) REFERENCES work_session (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE work_session_task ADD CONSTRAINT FK_967D89E58DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25ADF66B1A');
        $this->addSql('ALTER TABLE work_session DROP FOREIGN KEY FK_58F6DF6A76ED395');
        $this->addSql('ALTER TABLE work_session_task DROP FOREIGN KEY FK_967D89E57A5C410C');
        $this->addSql('ALTER TABLE work_session_task DROP FOREIGN KEY FK_967D89E58DB60186');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE work_session');
        $this->addSql('DROP TABLE work_session_task');
    }
}
