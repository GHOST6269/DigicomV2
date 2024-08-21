<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240807061429 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mouvement_stock ADD inventaire_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mouvement_stock ADD CONSTRAINT FK_61E2C8EBCE430A85 FOREIGN KEY (inventaire_id) REFERENCES inventaire (id)');
        $this->addSql('CREATE INDEX IDX_61E2C8EBCE430A85 ON mouvement_stock (inventaire_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mouvement_stock DROP FOREIGN KEY FK_61E2C8EBCE430A85');
        $this->addSql('DROP INDEX IDX_61E2C8EBCE430A85 ON mouvement_stock');
        $this->addSql('ALTER TABLE mouvement_stock DROP inventaire_id');
    }
}
