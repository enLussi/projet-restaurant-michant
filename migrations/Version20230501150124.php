<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230501150124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE menu ADD set_menu_id INT NOT NULL');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A934B7B0FF5 FOREIGN KEY (set_menu_id) REFERENCES set_menu (id)');
        $this->addSql('CREATE INDEX IDX_7D053A934B7B0FF5 ON menu (set_menu_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A934B7B0FF5');
        $this->addSql('DROP INDEX IDX_7D053A934B7B0FF5 ON menu');
        $this->addSql('ALTER TABLE menu DROP set_menu_id');
    }
}
