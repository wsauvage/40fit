<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260107095130 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE evaluation (id SERIAL NOT NULL, challenge_id INT DEFAULT NULL, associated_user_id INT NOT NULL, value DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1323A57598A21AC6 ON evaluation (challenge_id)');
        $this->addSql('CREATE INDEX IDX_1323A575BC272CD1 ON evaluation (associated_user_id)');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A57598A21AC6 FOREIGN KEY (challenge_id) REFERENCES challenge (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A575BC272CD1 FOREIGN KEY (associated_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE evaluation DROP CONSTRAINT FK_1323A57598A21AC6');
        $this->addSql('ALTER TABLE evaluation DROP CONSTRAINT FK_1323A575BC272CD1');
        $this->addSql('DROP TABLE evaluation');
    }
}
