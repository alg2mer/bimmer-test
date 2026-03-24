<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260324111942 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE software_version (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, system_version VARCHAR(255) DEFAULT NULL, system_version_alt VARCHAR(255) DEFAULT NULL, link CLOB DEFAULT NULL, st_link CLOB DEFAULT NULL, gd_link CLOB DEFAULT NULL, latest BOOLEAN NOT NULL, is_lci BOOLEAN NOT NULL, lci_type VARCHAR(255) DEFAULT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE software_version');
    }
}
