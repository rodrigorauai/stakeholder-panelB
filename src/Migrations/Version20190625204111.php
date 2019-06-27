<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190625204111 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Move address relationship counterpart from person to entity';
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81217BBB47');
        $this->addSql('DROP INDEX UNIQ_D4E6F81217BBB47 ON address');
        $this->addSql('ALTER TABLE address CHANGE person_id entity_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F8181257D5D FOREIGN KEY (entity_id) REFERENCES entity (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D4E6F8181257D5D ON address (entity_id)');
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F8181257D5D');
        $this->addSql('DROP INDEX UNIQ_D4E6F8181257D5D ON address');
        $this->addSql('ALTER TABLE address CHANGE entity_id person_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81217BBB47 FOREIGN KEY (person_id) REFERENCES entity (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D4E6F81217BBB47 ON address (person_id)');
    }
}
