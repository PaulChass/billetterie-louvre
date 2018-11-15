<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181115142336 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE reservation ADD created_ad DATETIME NOT NULL, DROP created_at');
        $this->addSql('ALTER TABLE ticket ADD reservation_id_id INT NOT NULL, DROP price, CHANGE country country VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA33C3B4EF0 FOREIGN KEY (reservation_id_id) REFERENCES reservation (id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA33C3B4EF0 ON ticket (reservation_id_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE reservation ADD created_at DATE NOT NULL, DROP created_ad');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA33C3B4EF0');
        $this->addSql('DROP INDEX IDX_97A0ADA33C3B4EF0 ON ticket');
        $this->addSql('ALTER TABLE ticket ADD price DOUBLE PRECISION NOT NULL, DROP reservation_id_id, CHANGE country country VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
