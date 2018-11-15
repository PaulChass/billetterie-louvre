<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181115135231 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE reservation ADD created_at DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', DROP amount_of_tickets, CHANGE date_time reservation_date DATETIME NOT NULL, CHANGE is_paid_for is_paid TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE ticket ADD many_to_one_id INT DEFAULT NULL, ADD birthday DATETIME NOT NULL, ADD type INT NOT NULL, DROP day_of_birth, CHANGE reservation_id reservation_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3EAB5DEB FOREIGN KEY (many_to_one_id) REFERENCES reservation (id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA33C3B4EF0 FOREIGN KEY (reservation_id_id) REFERENCES reservation (id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA3EAB5DEB ON ticket (many_to_one_id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA33C3B4EF0 ON ticket (reservation_id_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE reservation ADD amount_of_tickets INT NOT NULL, DROP created_at, CHANGE reservation_date date_time DATETIME NOT NULL, CHANGE is_paid is_paid_for TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3EAB5DEB');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA33C3B4EF0');
        $this->addSql('DROP INDEX IDX_97A0ADA3EAB5DEB ON ticket');
        $this->addSql('DROP INDEX IDX_97A0ADA33C3B4EF0 ON ticket');
        $this->addSql('ALTER TABLE ticket ADD reservation_id INT NOT NULL, ADD day_of_birth DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', DROP many_to_one_id, DROP reservation_id_id, DROP birthday, DROP type');
    }
}
