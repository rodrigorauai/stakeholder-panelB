<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190624172803 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add AbstractEntity, Account, BankAccount';
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD176979B1AD6');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81217BBB47');
        $this->addSql('ALTER TABLE phone DROP FOREIGN KEY FK_444F97DD217BBB47');
        $this->addSql('CREATE TABLE abstract_entity (id INT AUTO_INCREMENT NOT NULL, company_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, type VARCHAR(255) NOT NULL, cnpj VARCHAR(14) DEFAULT NULL, cpf VARCHAR(11) DEFAULT NULL, rg VARCHAR(32) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, roles JSON DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_DE3FA64B3E3E11F0 (cpf), UNIQUE INDEX UNIQ_DE3FA64B8F06FD70 (rg), UNIQUE INDEX UNIQ_DE3FA64BE7927C74 (email), INDEX IDX_DE3FA64B979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE account (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, balance NUMERIC(11, 2) NOT NULL, UNIQUE INDEX UNIQ_7D3656A47E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bank_account (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, bank VARCHAR(255) NOT NULL, bank_code VARCHAR(32) DEFAULT NULL, agency VARCHAR(16) NOT NULL, number VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, natural_person TINYINT(1) NOT NULL, holder_name VARCHAR(255) NOT NULL, holder_document_number VARCHAR(255) NOT NULL, notes VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_53A23E0A7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE abstract_entity ADD CONSTRAINT FK_DE3FA64B979B1AD6 FOREIGN KEY (company_id) REFERENCES abstract_entity (id)');
        $this->addSql('ALTER TABLE account ADD CONSTRAINT FK_7D3656A47E3C61F9 FOREIGN KEY (owner_id) REFERENCES abstract_entity (id)');
        $this->addSql('ALTER TABLE bank_account ADD CONSTRAINT FK_53A23E0A7E3C61F9 FOREIGN KEY (owner_id) REFERENCES abstract_entity (id)');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE person');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81217BBB47 FOREIGN KEY (person_id) REFERENCES abstract_entity (id)');
        $this->addSql('ALTER TABLE phone ADD CONSTRAINT FK_444F97DD217BBB47 FOREIGN KEY (person_id) REFERENCES abstract_entity (id)');
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE abstract_entity DROP FOREIGN KEY FK_DE3FA64B979B1AD6');
        $this->addSql('ALTER TABLE account DROP FOREIGN KEY FK_7D3656A47E3C61F9');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81217BBB47');
        $this->addSql('ALTER TABLE bank_account DROP FOREIGN KEY FK_53A23E0A7E3C61F9');
        $this->addSql('ALTER TABLE phone DROP FOREIGN KEY FK_444F97DD217BBB47');
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, cnpj VARCHAR(14) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, company_id INT DEFAULT NULL, roles JSON NOT NULL, password VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, cpf VARCHAR(11) DEFAULT NULL COLLATE utf8mb4_unicode_ci, rg VARCHAR(32) DEFAULT NULL COLLATE utf8mb4_unicode_ci, email VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, name VARCHAR(64) NOT NULL COLLATE utf8mb4_unicode_ci, UNIQUE INDEX UNIQ_34DCD1763E3E11F0 (cpf), UNIQUE INDEX UNIQ_34DCD1768F06FD70 (rg), UNIQUE INDEX UNIQ_34DCD176E7927C74 (email), INDEX IDX_34DCD176979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE abstract_entity');
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP TABLE bank_account');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE phone ADD CONSTRAINT FK_444F97DD217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
