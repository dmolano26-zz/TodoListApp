<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190520005540 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE persona_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE actividad_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE categoria_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE usuario_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE persona (id INT NOT NULL, nombres VARCHAR(50) NOT NULL, apellidos VARCHAR(50) NOT NULL, email VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE actividad (id INT NOT NULL, categoria_id INT NOT NULL, nombre VARCHAR(50) NOT NULL, descripcion VARCHAR(200) NOT NULL, estado INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8DF2BD063397707A ON actividad (categoria_id)');
        $this->addSql('CREATE TABLE categoria (id INT NOT NULL, nombre VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE usuario (id INT NOT NULL, persona_id INT NOT NULL, username VARCHAR(50) NOT NULL, password VARCHAR(200) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2265B05DF5F88DB9 ON usuario (persona_id)');
        $this->addSql('ALTER TABLE actividad ADD CONSTRAINT FK_8DF2BD063397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE usuario ADD CONSTRAINT FK_2265B05DF5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE usuario DROP CONSTRAINT FK_2265B05DF5F88DB9');
        $this->addSql('ALTER TABLE actividad DROP CONSTRAINT FK_8DF2BD063397707A');
        $this->addSql('DROP SEQUENCE persona_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE actividad_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE categoria_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE usuario_id_seq CASCADE');
        $this->addSql('DROP TABLE persona');
        $this->addSql('DROP TABLE actividad');
        $this->addSql('DROP TABLE categoria');
        $this->addSql('DROP TABLE usuario');
    }
}
