<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210506151947 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE buylist (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_4BE4BB7AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE card (id INT AUTO_INCREMENT NOT NULL, edition_id INT NOT NULL, collector_number VARCHAR(10) NOT NULL, name VARCHAR(255) NOT NULL, cmc DOUBLE PRECISION NOT NULL, color_identity LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\', rarity VARCHAR(255) NOT NULL, released_at DATE NOT NULL, is_reprint TINYINT(1) NOT NULL, layout VARCHAR(255) NOT NULL, border_color VARCHAR(255) NOT NULL, price_usd DOUBLE PRECISION DEFAULT NULL, price_eur DOUBLE PRECISION DEFAULT NULL, INDEX IDX_161498D374281A5E (edition_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE card_in_buylist (id INT AUTO_INCREMENT NOT NULL, buylist_id INT NOT NULL, card_name VARCHAR(255) NOT NULL, amount INT NOT NULL, INDEX IDX_6C25059A9F7179EF (buylist_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE card_in_collection (id INT AUTO_INCREMENT NOT NULL, card_id INT NOT NULL, user_id INT NOT NULL, language VARCHAR(2) NOT NULL, amount INT NOT NULL, INDEX IDX_C38AC4A54ACC9A20 (card_id), INDEX IDX_C38AC4A5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE card_in_deck (id INT AUTO_INCREMENT NOT NULL, deck_id INT NOT NULL, card_name VARCHAR(255) NOT NULL, amount INT NOT NULL, sideboard_amount INT NOT NULL, INDEX IDX_320D8325111948DC (deck_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deck (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, format VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, description LONGTEXT DEFAULT NULL, notes LONGTEXT DEFAULT NULL, INDEX IDX_4FAC3637A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE edition (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(10) NOT NULL, name VARCHAR(255) NOT NULL, set_type VARCHAR(255) NOT NULL, released_at DATE NOT NULL, block_code VARCHAR(255) DEFAULT NULL, block VARCHAR(255) DEFAULT NULL, icon_svg_uri VARCHAR(1000) NOT NULL, languages LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE face (id INT AUTO_INCREMENT NOT NULL, card_id INT NOT NULL, face_index INT NOT NULL, name VARCHAR(255) NOT NULL, mana_cost VARCHAR(255) NOT NULL, type_line VARCHAR(255) NOT NULL, oracle_text LONGTEXT DEFAULT NULL, flavor_text LONGTEXT DEFAULT NULL, power VARCHAR(10) DEFAULT NULL, toughness VARCHAR(10) DEFAULT NULL, loyalty VARCHAR(10) DEFAULT NULL, artist VARCHAR(255) DEFAULT NULL, watermark VARCHAR(255) DEFAULT NULL, image_uri_normal VARCHAR(1000) DEFAULT NULL, image_uri_png VARCHAR(1000) DEFAULT NULL, INDEX IDX_5147B674ACC9A20 (card_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE legality (id INT AUTO_INCREMENT NOT NULL, card_id INT NOT NULL, format VARCHAR(255) NOT NULL, legality VARCHAR(255) NOT NULL, INDEX IDX_DE37A5BE4ACC9A20 (card_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(1000) NOT NULL, auth_key VARCHAR(1000) DEFAULT NULL, languages LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE buylist ADD CONSTRAINT FK_4BE4BB7AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D374281A5E FOREIGN KEY (edition_id) REFERENCES edition (id)');
        $this->addSql('ALTER TABLE card_in_buylist ADD CONSTRAINT FK_6C25059A9F7179EF FOREIGN KEY (buylist_id) REFERENCES buylist (id)');
        $this->addSql('ALTER TABLE card_in_collection ADD CONSTRAINT FK_C38AC4A54ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id)');
        $this->addSql('ALTER TABLE card_in_collection ADD CONSTRAINT FK_C38AC4A5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE card_in_deck ADD CONSTRAINT FK_320D8325111948DC FOREIGN KEY (deck_id) REFERENCES deck (id)');
        $this->addSql('ALTER TABLE deck ADD CONSTRAINT FK_4FAC3637A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE face ADD CONSTRAINT FK_5147B674ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id)');
        $this->addSql('ALTER TABLE legality ADD CONSTRAINT FK_DE37A5BE4ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE card_in_buylist DROP FOREIGN KEY FK_6C25059A9F7179EF');
        $this->addSql('ALTER TABLE card_in_collection DROP FOREIGN KEY FK_C38AC4A54ACC9A20');
        $this->addSql('ALTER TABLE face DROP FOREIGN KEY FK_5147B674ACC9A20');
        $this->addSql('ALTER TABLE legality DROP FOREIGN KEY FK_DE37A5BE4ACC9A20');
        $this->addSql('ALTER TABLE card_in_deck DROP FOREIGN KEY FK_320D8325111948DC');
        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D374281A5E');
        $this->addSql('ALTER TABLE buylist DROP FOREIGN KEY FK_4BE4BB7AA76ED395');
        $this->addSql('ALTER TABLE card_in_collection DROP FOREIGN KEY FK_C38AC4A5A76ED395');
        $this->addSql('ALTER TABLE deck DROP FOREIGN KEY FK_4FAC3637A76ED395');
        $this->addSql('DROP TABLE buylist');
        $this->addSql('DROP TABLE card');
        $this->addSql('DROP TABLE card_in_buylist');
        $this->addSql('DROP TABLE card_in_collection');
        $this->addSql('DROP TABLE card_in_deck');
        $this->addSql('DROP TABLE deck');
        $this->addSql('DROP TABLE edition');
        $this->addSql('DROP TABLE face');
        $this->addSql('DROP TABLE legality');
        $this->addSql('DROP TABLE user');
    }
}
