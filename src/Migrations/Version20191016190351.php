<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191016190351 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE configuration_translate (id INT AUTO_INCREMENT NOT NULL, translate VARCHAR(32) NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('INSERT INTO configuration_translate (id, translate, active) VALUES (1, "BRL", 0)');
        $this->addSql('INSERT INTO configuration_translate (id, translate, active) VALUES (2, "USN", 1)');
        $this->addSql('ALTER TABLE contract ADD yield NUMERIC(11, 2) DEFAULT 0.00, ADD last NUMERIC(11, 2) DEFAULT 0.00');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE configuration_translate');
        $this->addSql('ALTER TABLE configuration CHANGE label label VARCHAR(16) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE contract DROP yield, DROP last');
    }
}
