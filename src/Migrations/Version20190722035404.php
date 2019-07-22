<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190722035404 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add invoice submittor';
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE payment_invoice ADD submittor_id INT NOT NULL');
        $this->addSql('ALTER TABLE payment_invoice ADD CONSTRAINT FK_892C19AEDB2E4DB2 FOREIGN KEY (submittor_id) REFERENCES entity (id)');
        $this->addSql('CREATE INDEX IDX_892C19AEDB2E4DB2 ON payment_invoice (submittor_id)');
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE payment_invoice DROP FOREIGN KEY FK_892C19AEDB2E4DB2');
        $this->addSql('DROP INDEX IDX_892C19AEDB2E4DB2 ON payment_invoice');
        $this->addSql('ALTER TABLE payment_invoice DROP submittor_id');
    }
}
