<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241128201416 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lignede_commande ADD produit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE lignede_commande ADD CONSTRAINT FK_9A8072B4F347EFB FOREIGN KEY (produit_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_9A8072B4F347EFB ON lignede_commande (produit_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lignede_commande DROP FOREIGN KEY FK_9A8072B4F347EFB');
        $this->addSql('DROP INDEX IDX_9A8072B4F347EFB ON lignede_commande');
        $this->addSql('ALTER TABLE lignede_commande DROP produit_id');
    }
}
