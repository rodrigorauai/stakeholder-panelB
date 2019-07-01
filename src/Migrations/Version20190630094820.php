<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190630094820 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add upload meta data to UploadedFile';
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE uploaded_file ADD uploader_id INT NOT NULL, ADD upload_timestamp DATETIME NOT NULL');
        $this->addSql('ALTER TABLE uploaded_file ADD CONSTRAINT FK_B40DF75D16678C77 FOREIGN KEY (uploader_id) REFERENCES entity (id)');
        $this->addSql('CREATE INDEX IDX_B40DF75D16678C77 ON uploaded_file (uploader_id)');
        $this->addSql('ALTER TABLE uploaded_invoice_file ADD upload_timestamp DATETIME NOT NULL');
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE uploaded_file DROP FOREIGN KEY FK_B40DF75D16678C77');
        $this->addSql('DROP INDEX IDX_B40DF75D16678C77 ON uploaded_file');
        $this->addSql('ALTER TABLE uploaded_file DROP uploader_id, DROP upload_timestamp');
        $this->addSql('ALTER TABLE uploaded_invoice_file DROP upload_timestamp');
    }
}
