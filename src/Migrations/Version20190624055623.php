<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190624055623 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add Company';
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, cnpj VARCHAR(14) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE person ADD company_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('CREATE INDEX IDX_34DCD176979B1AD6 ON person (company_id)');
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD176979B1AD6');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP INDEX IDX_34DCD176979B1AD6 ON person');
        $this->addSql('ALTER TABLE person DROP company_id');
    }
}
