<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190626191907 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Remove plan\' fixed reward rate and reward day range';
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE stakehold_plan ADD reward_day INT NOT NULL, DROP first_day_of_monthly_payment, DROP last_day_of_monthly_payment, DROP monthly_reward_rate');
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE stakehold_plan ADD last_day_of_monthly_payment INT NOT NULL, ADD monthly_reward_rate NUMERIC(10, 2) DEFAULT NULL, CHANGE reward_day first_day_of_monthly_payment INT NOT NULL');
    }
}
