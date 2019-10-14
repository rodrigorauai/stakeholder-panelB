<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190722091339 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add unique constraint to AuthenticationToken::string';
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE UNIQUE INDEX UNIQ_B54C4ADD9EBEB2A9 ON authentication_token (string)');
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_B54C4ADD9EBEB2A9 ON authentication_token');
    }
}
