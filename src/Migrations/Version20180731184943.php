<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180731184943 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE videos DROP FOREIGN KEY FK_29AA6432A76ED395');
        $this->addSql('DROP INDEX IDX_29AA6432A76ED395 ON videos');
        $this->addSql('ALTER TABLE videos CHANGE user_id iduser_id INT NOT NULL');
        $this->addSql('ALTER TABLE videos ADD CONSTRAINT FK_29AA6432786A81FB FOREIGN KEY (iduser_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_29AA6432786A81FB ON videos (iduser_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE videos DROP FOREIGN KEY FK_29AA6432786A81FB');
        $this->addSql('DROP INDEX IDX_29AA6432786A81FB ON videos');
        $this->addSql('ALTER TABLE videos CHANGE iduser_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE videos ADD CONSTRAINT FK_29AA6432A76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_29AA6432A76ED395 ON videos (user_id)');
    }
}
