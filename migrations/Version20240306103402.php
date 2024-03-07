<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306103402 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE client_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE client_account_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE client (id INT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE client_account (id INT NOT NULL, client_id INT DEFAULT NULL, currency VARCHAR(3) NOT NULL, balance DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2F0B12D919EB6921 ON client_account (client_id)');
        $this->addSql('ALTER TABLE client_account ADD CONSTRAINT FK_2F0B12D919EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE client_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE client_account_id_seq CASCADE');
        $this->addSql('ALTER TABLE client_account DROP CONSTRAINT FK_2F0B12D919EB6921');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE client_account');
    }
}
