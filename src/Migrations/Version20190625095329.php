<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190625095329 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE company_person (company_id INT NOT NULL, person_id INT NOT NULL, INDEX IDX_943B503979B1AD6 (company_id), INDEX IDX_943B503217BBB47 (person_id), PRIMARY KEY(company_id, person_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE company_person ADD CONSTRAINT FK_943B503979B1AD6 FOREIGN KEY (company_id) REFERENCES entity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE company_person ADD CONSTRAINT FK_943B503217BBB47 FOREIGN KEY (person_id) REFERENCES entity (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE person_company');
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE person_company (person_id INT NOT NULL, company_id INT NOT NULL, INDEX IDX_6D21BAC6979B1AD6 (company_id), INDEX IDX_6D21BAC6217BBB47 (person_id), PRIMARY KEY(person_id, company_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE person_company ADD CONSTRAINT FK_6D21BAC6217BBB47 FOREIGN KEY (person_id) REFERENCES entity (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE person_company ADD CONSTRAINT FK_6D21BAC6979B1AD6 FOREIGN KEY (company_id) REFERENCES entity (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('DROP TABLE company_person');
    }
}
