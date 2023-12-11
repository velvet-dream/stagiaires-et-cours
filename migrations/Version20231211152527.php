<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231211152527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cours_stagiaire (cours_id INT NOT NULL, stagiaire_id INT NOT NULL, INDEX IDX_B7C7CDF67ECF78B0 (cours_id), INDEX IDX_B7C7CDF6BBA93DD6 (stagiaire_id), PRIMARY KEY(cours_id, stagiaire_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cours_stagiaire ADD CONSTRAINT FK_B7C7CDF67ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cours_stagiaire ADD CONSTRAINT FK_B7C7CDF6BBA93DD6 FOREIGN KEY (stagiaire_id) REFERENCES stagiaire (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cours_stagiaire DROP FOREIGN KEY FK_B7C7CDF67ECF78B0');
        $this->addSql('ALTER TABLE cours_stagiaire DROP FOREIGN KEY FK_B7C7CDF6BBA93DD6');
        $this->addSql('DROP TABLE cours_stagiaire');
    }
}
