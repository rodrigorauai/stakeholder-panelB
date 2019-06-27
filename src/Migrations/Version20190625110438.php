<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190625110438 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Change person\'s phone to string';
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE phone');
        $this->addSql('ALTER TABLE entity ADD phone VARCHAR(16) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE phone (id INT AUTO_INCREMENT NOT NULL, person_id INT DEFAULT NULL, country VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, number VARCHAR(15) NOT NULL COLLATE utf8mb4_unicode_ci, INDEX IDX_444F97DD217BBB47 (person_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE phone ADD CONSTRAINT FK_444F97DD217BBB47 FOREIGN KEY (person_id) REFERENCES entity (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE entity DROP phone');
    }
}
