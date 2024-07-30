<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240730063653 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE unites_p ADD CONSTRAINT FK_4EDB8E27CAD6809C FOREIGN KEY (stock_depot_id) REFERENCES stock (id)');
        $this->addSql('ALTER TABLE unites_p ADD CONSTRAINT FK_4EDB8E278E409D4F FOREIGN KEY (stock_magasin_id) REFERENCES stock (id)');
        $this->addSql('CREATE INDEX IDX_4EDB8E27CAD6809C ON unites_p (stock_depot_id)');
        $this->addSql('CREATE INDEX IDX_4EDB8E278E409D4F ON unites_p (stock_magasin_id)');
        $this->addSql('ALTER TABLE vente CHANGE utilisateur_id utilisateur_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE unites_p DROP FOREIGN KEY FK_4EDB8E27CAD6809C');
        $this->addSql('ALTER TABLE unites_p DROP FOREIGN KEY FK_4EDB8E278E409D4F');
        $this->addSql('DROP INDEX IDX_4EDB8E27CAD6809C ON unites_p');
        $this->addSql('DROP INDEX IDX_4EDB8E278E409D4F ON unites_p');
        $this->addSql('ALTER TABLE vente CHANGE utilisateur_id utilisateur_id INT DEFAULT NULL');
    }
}
