<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190518210440 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE usuario ADD usuario VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE usuario ADD contraseña VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE usuario DROP username');
        $this->addSql('ALTER TABLE usuario DROP password');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE usuario ADD username VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE usuario ADD password VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE usuario DROP usuario');
        $this->addSql('ALTER TABLE usuario DROP contraseña');
    }
}
