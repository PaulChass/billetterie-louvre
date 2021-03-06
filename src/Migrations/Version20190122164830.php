<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190122164830 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE user_choices');
        $this->addSql('ALTER TABLE reservation CHANGE email_address email_address VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE ticket DROP email_address');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_choices (id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', day_tickets INT DEFAULT NULL, half_day_tickets INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Reservation CHANGE email_address email_address VARCHAR(200) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE ticket ADD email_address VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
