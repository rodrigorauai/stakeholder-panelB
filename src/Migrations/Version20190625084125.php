<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190625084125 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add AccountFinancialMovement, Payment, Withdraw and StakeholdPlanReward';
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE account_financial_movement (id INT AUTO_INCREMENT NOT NULL, account_id INT DEFAULT NULL, interest_id INT DEFAULT NULL, contract_id INT DEFAULT NULL, beneficiary_id INT DEFAULT NULL, bank_account_id INT DEFAULT NULL, requester_id INT DEFAULT NULL, operator_id INT DEFAULT NULL, value NUMERIC(11, 2) NOT NULL, timestamp DATETIME NOT NULL, type VARCHAR(255) NOT NULL, provenance VARCHAR(32) DEFAULT NULL, status VARCHAR(32) DEFAULT NULL, request_timestamp DATETIME DEFAULT NULL, operation_timestamp DATETIME DEFAULT NULL, INDEX IDX_7E6B47A99B6B5FBA (account_id), INDEX IDX_7E6B47A95A95FF89 (interest_id), INDEX IDX_7E6B47A92576E0FD (contract_id), INDEX IDX_7E6B47A9ECCAAFA0 (beneficiary_id), INDEX IDX_7E6B47A912CB990C (bank_account_id), INDEX IDX_7E6B47A9ED442CF4 (requester_id), INDEX IDX_7E6B47A9584598A3 (operator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stakehold_plan_reward (id INT AUTO_INCREMENT NOT NULL, plan_id INT DEFAULT NULL, rate NUMERIC(5, 2) NOT NULL, first_payment_date DATE NOT NULL, last_payment_date DATE NOT NULL, reference VARCHAR(6) NOT NULL, UNIQUE INDEX UNIQ_59A6B3DEAEA34913 (reference), INDEX IDX_59A6B3DEE899029B (plan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE account_financial_movement ADD CONSTRAINT FK_7E6B47A99B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE account_financial_movement ADD CONSTRAINT FK_7E6B47A95A95FF89 FOREIGN KEY (interest_id) REFERENCES stakehold_plan_reward (id)');
        $this->addSql('ALTER TABLE account_financial_movement ADD CONSTRAINT FK_7E6B47A92576E0FD FOREIGN KEY (contract_id) REFERENCES contract (id)');
        $this->addSql('ALTER TABLE account_financial_movement ADD CONSTRAINT FK_7E6B47A9ECCAAFA0 FOREIGN KEY (beneficiary_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE account_financial_movement ADD CONSTRAINT FK_7E6B47A912CB990C FOREIGN KEY (bank_account_id) REFERENCES bank_account (id)');
        $this->addSql('ALTER TABLE account_financial_movement ADD CONSTRAINT FK_7E6B47A9ED442CF4 FOREIGN KEY (requester_id) REFERENCES abstract_entity (id)');
        $this->addSql('ALTER TABLE account_financial_movement ADD CONSTRAINT FK_7E6B47A9584598A3 FOREIGN KEY (operator_id) REFERENCES abstract_entity (id)');
        $this->addSql('ALTER TABLE stakehold_plan_reward ADD CONSTRAINT FK_59A6B3DEE899029B FOREIGN KEY (plan_id) REFERENCES stakehold_plan (id)');
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE account_financial_movement DROP FOREIGN KEY FK_7E6B47A95A95FF89');
        $this->addSql('DROP TABLE account_financial_movement');
        $this->addSql('DROP TABLE stakehold_plan_reward');
    }
}
