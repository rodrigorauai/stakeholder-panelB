<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190722031438 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Update payment invoice structure';
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE payment_invoice (id INT AUTO_INCREMENT NOT NULL, revisor_id INT DEFAULT NULL, payment_id INT NOT NULL, url VARCHAR(255) NOT NULL, status SMALLINT NOT NULL, date_sent DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', date_revised DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', INDEX IDX_892C19AEBD3183DF (revisor_id), INDEX IDX_892C19AE4C3A3BB (payment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE payment_invoice ADD CONSTRAINT FK_892C19AEBD3183DF FOREIGN KEY (revisor_id) REFERENCES entity (id)');
        $this->addSql('ALTER TABLE payment_invoice ADD CONSTRAINT FK_892C19AE4C3A3BB FOREIGN KEY (payment_id) REFERENCES account_financial_movement (id)');
        $this->addSql('ALTER TABLE account_financial_movement DROP invoice_url');
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE payment_invoice');
        $this->addSql('ALTER TABLE account_financial_movement ADD invoice_url VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
