<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240802131143 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mouvement_stock ADD achat_id INT NOT NULL');
        $this->addSql('ALTER TABLE mouvement_stock ADD CONSTRAINT FK_61E2C8EBFE95D117 FOREIGN KEY (achat_id) REFERENCES achat (id)');
        $this->addSql('CREATE INDEX IDX_61E2C8EBFE95D117 ON mouvement_stock (achat_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mouvement_stock DROP FOREIGN KEY FK_61E2C8EBFE95D117');
        $this->addSql('DROP INDEX IDX_61E2C8EBFE95D117 ON mouvement_stock');
        $this->addSql('ALTER TABLE mouvement_stock DROP achat_id');
    }
}
