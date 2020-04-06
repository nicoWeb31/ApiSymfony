<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200406095853 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE nationalite (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE auteurs ADD CONSTRAINT FK_6DD7D42A1B063272 FOREIGN KEY (nationalite_id) REFERENCES nationalite (id)');
        $this->addSql('CREATE INDEX IDX_6DD7D42A1B063272 ON auteurs (nationalite_id)');
        $this->addSql('ALTER TABLE livre CHANGE prix prix DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE auteurs DROP FOREIGN KEY FK_6DD7D42A1B063272');
        $this->addSql('DROP TABLE nationalite');
        $this->addSql('DROP INDEX IDX_6DD7D42A1B063272 ON auteurs');
        $this->addSql('ALTER TABLE livre CHANGE prix prix DOUBLE PRECISION DEFAULT \'NULL\'');
    }
}
