<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190716120716 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add UploadedPersonFile';
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE uploaded_file ADD person_id INT DEFAULT NULL, ADD name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE uploaded_file ADD CONSTRAINT FK_B40DF75D217BBB47 FOREIGN KEY (person_id) REFERENCES entity (id)');
        $this->addSql('CREATE INDEX IDX_B40DF75D217BBB47 ON uploaded_file (person_id)');
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE uploaded_file DROP FOREIGN KEY FK_B40DF75D217BBB47');
        $this->addSql('DROP INDEX IDX_B40DF75D217BBB47 ON uploaded_file');
        $this->addSql('ALTER TABLE uploaded_file DROP person_id, DROP name');
    }
}
