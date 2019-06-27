<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190625091144 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Rename AbstractEntity to Entity';
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE abstract_entity DROP FOREIGN KEY FK_DE3FA64B979B1AD6');
        $this->addSql('ALTER TABLE account DROP FOREIGN KEY FK_7D3656A47E3C61F9');
        $this->addSql('ALTER TABLE account_financial_movement DROP FOREIGN KEY FK_7E6B47A9584598A3');
        $this->addSql('ALTER TABLE account_financial_movement DROP FOREIGN KEY FK_7E6B47A9ED442CF4');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81217BBB47');
        $this->addSql('ALTER TABLE bank_account DROP FOREIGN KEY FK_53A23E0A7E3C61F9');
        $this->addSql('ALTER TABLE phone DROP FOREIGN KEY FK_444F97DD217BBB47');
        $this->addSql('CREATE TABLE entity (id INT AUTO_INCREMENT NOT NULL, company_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, type VARCHAR(255) NOT NULL, cpf VARCHAR(11) DEFAULT NULL, rg VARCHAR(32) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, roles JSON DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, cnpj VARCHAR(14) DEFAULT NULL, UNIQUE INDEX UNIQ_E2844683E3E11F0 (cpf), UNIQUE INDEX UNIQ_E2844688F06FD70 (rg), UNIQUE INDEX UNIQ_E284468E7927C74 (email), INDEX IDX_E284468979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE entity ADD CONSTRAINT FK_E284468979B1AD6 FOREIGN KEY (company_id) REFERENCES entity (id)');
        $this->addSql('DROP TABLE abstract_entity');
        $this->addSql('ALTER TABLE account ADD CONSTRAINT FK_7D3656A47E3C61F9 FOREIGN KEY (owner_id) REFERENCES entity (id)');
        $this->addSql('ALTER TABLE account_financial_movement ADD CONSTRAINT FK_7E6B47A9584598A3 FOREIGN KEY (operator_id) REFERENCES entity (id)');
        $this->addSql('ALTER TABLE account_financial_movement ADD CONSTRAINT FK_7E6B47A9ED442CF4 FOREIGN KEY (requester_id) REFERENCES entity (id)');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81217BBB47 FOREIGN KEY (person_id) REFERENCES entity (id)');
        $this->addSql('ALTER TABLE bank_account ADD CONSTRAINT FK_53A23E0A7E3C61F9 FOREIGN KEY (owner_id) REFERENCES entity (id)');
        $this->addSql('ALTER TABLE phone ADD CONSTRAINT FK_444F97DD217BBB47 FOREIGN KEY (person_id) REFERENCES entity (id)');
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE entity DROP FOREIGN KEY FK_E284468979B1AD6');
        $this->addSql('ALTER TABLE account DROP FOREIGN KEY FK_7D3656A47E3C61F9');
        $this->addSql('ALTER TABLE account_financial_movement DROP FOREIGN KEY FK_7E6B47A9ED442CF4');
        $this->addSql('ALTER TABLE account_financial_movement DROP FOREIGN KEY FK_7E6B47A9584598A3');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81217BBB47');
        $this->addSql('ALTER TABLE bank_account DROP FOREIGN KEY FK_53A23E0A7E3C61F9');
        $this->addSql('ALTER TABLE phone DROP FOREIGN KEY FK_444F97DD217BBB47');
        $this->addSql('CREATE TABLE abstract_entity (id INT AUTO_INCREMENT NOT NULL, company_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL COLLATE utf8mb4_unicode_ci, type VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, cnpj VARCHAR(14) DEFAULT NULL COLLATE utf8mb4_unicode_ci, cpf VARCHAR(11) DEFAULT NULL COLLATE utf8mb4_unicode_ci, rg VARCHAR(32) DEFAULT NULL COLLATE utf8mb4_unicode_ci, email VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, roles JSON DEFAULT NULL, password VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, UNIQUE INDEX UNIQ_DE3FA64B3E3E11F0 (cpf), UNIQUE INDEX UNIQ_DE3FA64B8F06FD70 (rg), UNIQUE INDEX UNIQ_DE3FA64BE7927C74 (email), INDEX IDX_DE3FA64B979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE abstract_entity ADD CONSTRAINT FK_DE3FA64B979B1AD6 FOREIGN KEY (company_id) REFERENCES abstract_entity (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE entity');
        $this->addSql('ALTER TABLE account ADD CONSTRAINT FK_7D3656A47E3C61F9 FOREIGN KEY (owner_id) REFERENCES abstract_entity (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE account_financial_movement ADD CONSTRAINT FK_7E6B47A9ED442CF4 FOREIGN KEY (requester_id) REFERENCES abstract_entity (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE account_financial_movement ADD CONSTRAINT FK_7E6B47A9584598A3 FOREIGN KEY (operator_id) REFERENCES abstract_entity (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81217BBB47 FOREIGN KEY (person_id) REFERENCES abstract_entity (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE bank_account ADD CONSTRAINT FK_53A23E0A7E3C61F9 FOREIGN KEY (owner_id) REFERENCES abstract_entity (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE phone ADD CONSTRAINT FK_444F97DD217BBB47 FOREIGN KEY (person_id) REFERENCES abstract_entity (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
