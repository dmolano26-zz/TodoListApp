<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190518221554 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE actividad_categoria');
        $this->addSql('ALTER TABLE actividad ADD categoria_id INT NOT NULL');
        $this->addSql('ALTER TABLE actividad ADD CONSTRAINT FK_8DF2BD063397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8DF2BD063397707A ON actividad (categoria_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE actividad_categoria (actividad_id INT NOT NULL, categoria_id INT NOT NULL, PRIMARY KEY(actividad_id, categoria_id))');
        $this->addSql('CREATE INDEX idx_e3367cb83397707a ON actividad_categoria (categoria_id)');
        $this->addSql('CREATE INDEX idx_e3367cb86014faca ON actividad_categoria (actividad_id)');
        $this->addSql('ALTER TABLE actividad_categoria ADD CONSTRAINT fk_e3367cb86014faca FOREIGN KEY (actividad_id) REFERENCES actividad (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE actividad_categoria ADD CONSTRAINT fk_e3367cb83397707a FOREIGN KEY (categoria_id) REFERENCES categoria (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE actividad DROP CONSTRAINT FK_8DF2BD063397707A');
        $this->addSql('DROP INDEX IDX_8DF2BD063397707A');
        $this->addSql('ALTER TABLE actividad DROP categoria_id');
    }
}
