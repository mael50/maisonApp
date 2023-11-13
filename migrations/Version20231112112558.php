<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231112112558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE bills (id INT AUTO_INCREMENT NOT NULL, employee_id INT DEFAULT NULL, month DATETIME NOT NULL, INDEX IDX_22775DD08C03F15C (employee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE work_session ADD bills_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE work_session ADD CONSTRAINT FK_58F6DF62ABC37A4 FOREIGN KEY (bills_id) REFERENCES bills (id)');
        $this->addSql('CREATE INDEX IDX_58F6DF62ABC37A4 ON work_session (bills_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE work_session DROP FOREIGN KEY FK_58F6DF62ABC37A4');
        $this->addSql('ALTER TABLE bills DROP FOREIGN KEY FK_22775DD08C03F15C');
        $this->addSql('DROP TABLE bills');
        $this->addSql('DROP INDEX IDX_58F6DF62ABC37A4 ON work_session');
        $this->addSql('ALTER TABLE work_session DROP bills_id');
    }
}
