<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190627075950 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Remove duplicated association between financial movement and account and rename payment\'s interest to reward';
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE account_financial_movement DROP FOREIGN KEY FK_7E6B47A95A95FF89');
        $this->addSql('ALTER TABLE account_financial_movement DROP FOREIGN KEY FK_7E6B47A9ECCAAFA0');
        $this->addSql('DROP INDEX IDX_7E6B47A95A95FF89 ON account_financial_movement');
        $this->addSql('DROP INDEX IDX_7E6B47A9ECCAAFA0 ON account_financial_movement');
        $this->addSql('ALTER TABLE account_financial_movement ADD reward_id INT DEFAULT NULL, DROP interest_id, DROP beneficiary_id');
        $this->addSql('ALTER TABLE account_financial_movement ADD CONSTRAINT FK_7E6B47A9E466ACA1 FOREIGN KEY (reward_id) REFERENCES stakehold_plan_reward (id)');
        $this->addSql('CREATE INDEX IDX_7E6B47A9E466ACA1 ON account_financial_movement (reward_id)');
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE account_financial_movement DROP FOREIGN KEY FK_7E6B47A9E466ACA1');
        $this->addSql('DROP INDEX IDX_7E6B47A9E466ACA1 ON account_financial_movement');
        $this->addSql('ALTER TABLE account_financial_movement ADD beneficiary_id INT DEFAULT NULL, CHANGE reward_id interest_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE account_financial_movement ADD CONSTRAINT FK_7E6B47A95A95FF89 FOREIGN KEY (interest_id) REFERENCES stakehold_plan_reward (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE account_financial_movement ADD CONSTRAINT FK_7E6B47A9ECCAAFA0 FOREIGN KEY (beneficiary_id) REFERENCES account (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_7E6B47A95A95FF89 ON account_financial_movement (interest_id)');
        $this->addSql('CREATE INDEX IDX_7E6B47A9ECCAAFA0 ON account_financial_movement (beneficiary_id)');
    }
}
