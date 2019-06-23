<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190623193019 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add StakeholdingPlan';
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE stakehold_plan (id INT AUTO_INCREMENT NOT NULL, administrative_name VARCHAR(255) NOT NULL, commercial_name VARCHAR(255) NOT NULL, minimum_value NUMERIC(10, 2) NOT NULL, value_multiple NUMERIC(10, 2) NOT NULL, first_day_of_monthly_payment INT NOT NULL, last_day_of_monthly_payment INT NOT NULL, grace_period INT NOT NULL, best_acquisition_day INT NOT NULL, monthly_commission NUMERIC(4, 2) NOT NULL, monthly_administrative_fee NUMERIC(4, 2) NOT NULL, yield_fixed TINYINT(1) NOT NULL, monthly_yield NUMERIC(10, 2) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE stakehold_plan');
    }
}
