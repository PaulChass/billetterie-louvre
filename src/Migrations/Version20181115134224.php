<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181115134224 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE reservation ADD created_at DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', DROP amount_of_tickets, CHANGE date_time reservation_date DATETIME NOT NULL, CHANGE is_paid_for is_paid TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE ticket ADD birthday DATETIME NOT NULL, DROP day_of_birth, CHANGE reservation_id type INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE reservation ADD amount_of_tickets INT NOT NULL, DROP created_at, CHANGE reservation_date date_time DATETIME NOT NULL, CHANGE is_paid is_paid_for TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE ticket ADD day_of_birth DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', DROP birthday, CHANGE type reservation_id INT NOT NULL');
    }
}
