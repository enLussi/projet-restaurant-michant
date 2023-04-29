<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230429145618 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, lastname VARCHAR(50) NOT NULL, firstname VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_880E0D76E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE allergen (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE allergen_booking (allergen_id INT NOT NULL, booking_id INT NOT NULL, INDEX IDX_2A3A16156E775A4A (allergen_id), INDEX IDX_2A3A16153301C60 (booking_id), PRIMARY KEY(allergen_id, booking_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, booking_date DATETIME NOT NULL, covers SMALLINT NOT NULL, INDEX IDX_E00CEDDE9395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE course (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, title VARCHAR(100) NOT NULL, summary LONGTEXT NOT NULL, price SMALLINT NOT NULL, INDEX IDX_169E6FB912469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE course_category (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, lastname VARCHAR(50) NOT NULL, firstname VARCHAR(50) NOT NULL, phone VARCHAR(10) DEFAULT NULL, UNIQUE INDEX UNIQ_81398E09E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer_allergen (customer_id INT NOT NULL, allergen_id INT NOT NULL, INDEX IDX_97A4BE19395C3F3 (customer_id), INDEX IDX_97A4BE16E775A4A (allergen_id), PRIMARY KEY(customer_id, allergen_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hours (id INT AUTO_INCREMENT NOT NULL, label_day VARCHAR(20) NOT NULL, opening VARCHAR(5) NOT NULL, closure VARCHAR(5) NOT NULL, open TINYINT(1) NOT NULL, lunch TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_course (menu_id INT NOT NULL, course_id INT NOT NULL, INDEX IDX_C5D93761CCD7E912 (menu_id), INDEX IDX_C5D93761591CC992 (course_id), PRIMARY KEY(menu_id, course_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE set_menu (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, summary LONGTEXT NOT NULL, price SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE set_menu_course_category (set_menu_id INT NOT NULL, course_category_id INT NOT NULL, INDEX IDX_181E3F5D4B7B0FF5 (set_menu_id), INDEX IDX_181E3F5D6628AD36 (course_category_id), PRIMARY KEY(set_menu_id, course_category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, lastname VARCHAR(50) NOT NULL, firstname VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE allergen_booking ADD CONSTRAINT FK_2A3A16156E775A4A FOREIGN KEY (allergen_id) REFERENCES allergen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE allergen_booking ADD CONSTRAINT FK_2A3A16153301C60 FOREIGN KEY (booking_id) REFERENCES booking (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB912469DE2 FOREIGN KEY (category_id) REFERENCES course_category (id)');
        $this->addSql('ALTER TABLE customer_allergen ADD CONSTRAINT FK_97A4BE19395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE customer_allergen ADD CONSTRAINT FK_97A4BE16E775A4A FOREIGN KEY (allergen_id) REFERENCES allergen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_course ADD CONSTRAINT FK_C5D93761CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_course ADD CONSTRAINT FK_C5D93761591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE set_menu_course_category ADD CONSTRAINT FK_181E3F5D4B7B0FF5 FOREIGN KEY (set_menu_id) REFERENCES set_menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE set_menu_course_category ADD CONSTRAINT FK_181E3F5D6628AD36 FOREIGN KEY (course_category_id) REFERENCES course_category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE allergen_booking DROP FOREIGN KEY FK_2A3A16156E775A4A');
        $this->addSql('ALTER TABLE allergen_booking DROP FOREIGN KEY FK_2A3A16153301C60');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE9395C3F3');
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB912469DE2');
        $this->addSql('ALTER TABLE customer_allergen DROP FOREIGN KEY FK_97A4BE19395C3F3');
        $this->addSql('ALTER TABLE customer_allergen DROP FOREIGN KEY FK_97A4BE16E775A4A');
        $this->addSql('ALTER TABLE menu_course DROP FOREIGN KEY FK_C5D93761CCD7E912');
        $this->addSql('ALTER TABLE menu_course DROP FOREIGN KEY FK_C5D93761591CC992');
        $this->addSql('ALTER TABLE set_menu_course_category DROP FOREIGN KEY FK_181E3F5D4B7B0FF5');
        $this->addSql('ALTER TABLE set_menu_course_category DROP FOREIGN KEY FK_181E3F5D6628AD36');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE allergen');
        $this->addSql('DROP TABLE allergen_booking');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE course');
        $this->addSql('DROP TABLE course_category');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE customer_allergen');
        $this->addSql('DROP TABLE hours');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE menu_course');
        $this->addSql('DROP TABLE set_menu');
        $this->addSql('DROP TABLE set_menu_course_category');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
