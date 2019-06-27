<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190622204014 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add User, Add and Phone';
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, person_id INT DEFAULT NULL, postal_code VARCHAR(32) NOT NULL, street VARCHAR(255) NOT NULL, number VARCHAR(15) NOT NULL, district VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, state VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_D4E6F81217BBB47 (person_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phone (id INT AUTO_INCREMENT NOT NULL, person_id INT DEFAULT NULL, country VARCHAR(255) NOT NULL, number VARCHAR(15) NOT NULL, INDEX IDX_444F97DD217BBB47 (person_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE phone ADD CONSTRAINT FK_444F97DD217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('DROP INDEX UNIQ_34DCD176DC9EBDFB ON person');
        $this->addSql('ALTER TABLE person ADD cpf VARCHAR(11) DEFAULT NULL, ADD rg VARCHAR(32) DEFAULT NULL, ADD email VARCHAR(255) NOT NULL, DROP identity_document_number');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_34DCD1763E3E11F0 ON person (cpf)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_34DCD1768F06FD70 ON person (rg)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_34DCD176E7927C74 ON person (email)');
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE phone');
        $this->addSql('DROP INDEX UNIQ_34DCD1763E3E11F0 ON person');
        $this->addSql('DROP INDEX UNIQ_34DCD1768F06FD70 ON person');
        $this->addSql('DROP INDEX UNIQ_34DCD176E7927C74 ON person');
        $this->addSql('ALTER TABLE person ADD identity_document_number VARCHAR(180) DEFAULT NULL COLLATE utf8mb4_unicode_ci, DROP cpf, DROP rg, DROP email');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_34DCD176DC9EBDFB ON person (identity_document_number)');
    }
}
