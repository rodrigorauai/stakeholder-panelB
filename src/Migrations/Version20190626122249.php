<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190626122249 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add trade representative as a property of Entities';
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE entity ADD trade_representative_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE entity ADD CONSTRAINT FK_E2844682E4A4A28 FOREIGN KEY (trade_representative_id) REFERENCES entity (id)');
        $this->addSql('CREATE INDEX IDX_E2844682E4A4A28 ON entity (trade_representative_id)');
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE entity DROP FOREIGN KEY FK_E2844682E4A4A28');
        $this->addSql('DROP INDEX IDX_E2844682E4A4A28 ON entity');
        $this->addSql('ALTER TABLE entity DROP trade_representative_id');
    }
}
