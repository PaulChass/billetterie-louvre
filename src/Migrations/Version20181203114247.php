<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181203114247 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE user_choices');
        $this->addSql('ALTER TABLE reservation CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE ticket RENAME INDEX reservation_id TO IDX_97A0ADA3B83297E7');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_choices (id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', day_tickets INT DEFAULT NULL, half_day_tickets INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reservation CHANGE created_at created_at DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\'');
        $this->addSql('ALTER TABLE ticket RENAME INDEX idx_97a0ada3b83297e7 TO reservation_id');
    }
}
