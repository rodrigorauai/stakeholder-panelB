<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190630095116 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Update inheritance type of UploadedFile to single-table';
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE uploaded_invoice_file');
        $this->addSql('ALTER TABLE uploaded_file ADD withdraw_id INT NOT NULL, ADD dtype VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE uploaded_file ADD CONSTRAINT FK_B40DF75DCD84EE37 FOREIGN KEY (withdraw_id) REFERENCES account_financial_movement (id)');
        $this->addSql('CREATE INDEX IDX_B40DF75DCD84EE37 ON uploaded_file (withdraw_id)');
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE uploaded_invoice_file (id INT AUTO_INCREMENT NOT NULL, withdraw_id INT NOT NULL, path VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, upload_timestamp DATETIME NOT NULL, INDEX IDX_97575379CD84EE37 (withdraw_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE uploaded_invoice_file ADD CONSTRAINT FK_97575379CD84EE37 FOREIGN KEY (withdraw_id) REFERENCES account_financial_movement (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE uploaded_file DROP FOREIGN KEY FK_B40DF75DCD84EE37');
        $this->addSql('DROP INDEX IDX_B40DF75DCD84EE37 ON uploaded_file');
        $this->addSql('ALTER TABLE uploaded_file DROP withdraw_id, DROP dtype');
    }
}
