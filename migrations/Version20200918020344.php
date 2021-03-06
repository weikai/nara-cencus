<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200918020344 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE street (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(128) NOT NULL, UNIQUE INDEX UNIQ_F0EED3D85E237E06 (name), FULLTEXT INDEX IDX_F0EED3D85E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE state (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(40) NOT NULL, abbr VARCHAR(2) NOT NULL, UNIQUE INDEX UNIQ_A393D2FB5E237E06 (name), UNIQUE INDEX UNIQ_A393D2FB4D4901 (abbr), FULLTEXT INDEX IDX_A393D2FB5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE record_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(20) NOT NULL, label VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE county (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(40) NOT NULL, UNIQUE INDEX UNIQ_58E2FF255E237E06 (name), FULLTEXT INDEX IDX_58E2FF255E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ed_summary (id INT AUTO_INCREMENT NOT NULL, state_id INT NOT NULL, county_id INT NOT NULL, ed VARCHAR(20) NOT NULL, description VARCHAR(2048) DEFAULT NULL, statename VARCHAR(40) NOT NULL, stateabbr VARCHAR(4) NOT NULL, countyname VARCHAR(40) NOT NULL, cityname VARCHAR(40) DEFAULT NULL, year SMALLINT NOT NULL, sortkey VARCHAR(20) NOT NULL, UNIQUE INDEX UNIQ_A892F9D138C285C (ed), INDEX IDX_A892F9D5D83CC1 (state_id), INDEX IDX_A892F9D85E73F45 (county_id), FULLTEXT INDEX IDX_A892F9D138C285C6DE4402694FA2575CA9412727EAF5D4F3E7E1D74 (ed, description, statename, stateabbr, countyname, cityname), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_2D5B02345E237E06 (name), FULLTEXT INDEX IDX_2D5B02345E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE enumeration (id INT AUTO_INCREMENT NOT NULL, state_id INT NOT NULL, county_id INT NOT NULL, city_id INT DEFAULT NULL, ed_id INT NOT NULL, INDEX IDX_A4F2E1835D83CC1 (state_id), INDEX IDX_A4F2E18385E73F45 (county_id), INDEX IDX_A4F2E1838BAC62AF (city_id), INDEX IDX_A4F2E183BAB47356 (ed_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city_state (id INT AUTO_INCREMENT NOT NULL, state_id INT NOT NULL, county_id INT NOT NULL, city_id INT NOT NULL, INDEX IDX_E01975AC5D83CC1 (state_id), INDEX IDX_E01975AC85E73F45 (county_id), INDEX IDX_E01975AC8BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE census_image (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, state_id INT NOT NULL, county_id INT DEFAULT NULL, city_id INT DEFAULT NULL, enum_id INT DEFAULT NULL, publication VARCHAR(20) NOT NULL, rollnum VARCHAR(20) NOT NULL, imgseq VARCHAR(10) NOT NULL, filename VARCHAR(128) NOT NULL, year SMALLINT NOT NULL, INDEX IDX_C46D951CC54C8C93 (type_id), INDEX IDX_C46D951C5D83CC1 (state_id), INDEX IDX_C46D951C85E73F45 (county_id), INDEX IDX_C46D951C8BAC62AF (city_id), INDEX IDX_C46D951C17628E55 (enum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, street_id INT NOT NULL, city_id INT NOT NULL, state_id INT NOT NULL, INDEX IDX_D4E6F8187CF8EB (street_id), INDEX IDX_D4E6F818BAC62AF (city_id), INDEX IDX_D4E6F815D83CC1 (state_id), UNIQUE INDEX search_idx (street_id, city_id, state_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ed_summary ADD CONSTRAINT FK_A892F9D5D83CC1 FOREIGN KEY (state_id) REFERENCES state (id)');
        $this->addSql('ALTER TABLE ed_summary ADD CONSTRAINT FK_A892F9D85E73F45 FOREIGN KEY (county_id) REFERENCES county (id)');
        $this->addSql('ALTER TABLE enumeration ADD CONSTRAINT FK_A4F2E1835D83CC1 FOREIGN KEY (state_id) REFERENCES state (id)');
        $this->addSql('ALTER TABLE enumeration ADD CONSTRAINT FK_A4F2E18385E73F45 FOREIGN KEY (county_id) REFERENCES county (id)');
        $this->addSql('ALTER TABLE enumeration ADD CONSTRAINT FK_A4F2E1838BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE enumeration ADD CONSTRAINT FK_A4F2E183BAB47356 FOREIGN KEY (ed_id) REFERENCES ed_summary (id)');
        $this->addSql('ALTER TABLE city_state ADD CONSTRAINT FK_E01975AC5D83CC1 FOREIGN KEY (state_id) REFERENCES state (id)');
        $this->addSql('ALTER TABLE city_state ADD CONSTRAINT FK_E01975AC85E73F45 FOREIGN KEY (county_id) REFERENCES county (id)');
        $this->addSql('ALTER TABLE city_state ADD CONSTRAINT FK_E01975AC8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE census_image ADD CONSTRAINT FK_C46D951CC54C8C93 FOREIGN KEY (type_id) REFERENCES record_type (id)');
        $this->addSql('ALTER TABLE census_image ADD CONSTRAINT FK_C46D951C5D83CC1 FOREIGN KEY (state_id) REFERENCES state (id)');
        $this->addSql('ALTER TABLE census_image ADD CONSTRAINT FK_C46D951C85E73F45 FOREIGN KEY (county_id) REFERENCES county (id)');
        $this->addSql('ALTER TABLE census_image ADD CONSTRAINT FK_C46D951C8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE census_image ADD CONSTRAINT FK_C46D951C17628E55 FOREIGN KEY (enum_id) REFERENCES enumeration (id)');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F8187CF8EB FOREIGN KEY (street_id) REFERENCES street (id)');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F818BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F815D83CC1 FOREIGN KEY (state_id) REFERENCES state (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F818BAC62AF');
        $this->addSql('ALTER TABLE census_image DROP FOREIGN KEY FK_C46D951C8BAC62AF');
        $this->addSql('ALTER TABLE city_state DROP FOREIGN KEY FK_E01975AC8BAC62AF');
        $this->addSql('ALTER TABLE enumeration DROP FOREIGN KEY FK_A4F2E1838BAC62AF');
        $this->addSql('ALTER TABLE census_image DROP FOREIGN KEY FK_C46D951C85E73F45');
        $this->addSql('ALTER TABLE city_state DROP FOREIGN KEY FK_E01975AC85E73F45');
        $this->addSql('ALTER TABLE ed_summary DROP FOREIGN KEY FK_A892F9D85E73F45');
        $this->addSql('ALTER TABLE enumeration DROP FOREIGN KEY FK_A4F2E18385E73F45');
        $this->addSql('ALTER TABLE enumeration DROP FOREIGN KEY FK_A4F2E183BAB47356');
        $this->addSql('ALTER TABLE census_image DROP FOREIGN KEY FK_C46D951C17628E55');
        $this->addSql('ALTER TABLE census_image DROP FOREIGN KEY FK_C46D951CC54C8C93');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F815D83CC1');
        $this->addSql('ALTER TABLE census_image DROP FOREIGN KEY FK_C46D951C5D83CC1');
        $this->addSql('ALTER TABLE city_state DROP FOREIGN KEY FK_E01975AC5D83CC1');
        $this->addSql('ALTER TABLE ed_summary DROP FOREIGN KEY FK_A892F9D5D83CC1');
        $this->addSql('ALTER TABLE enumeration DROP FOREIGN KEY FK_A4F2E1835D83CC1');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F8187CF8EB');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE census_image');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE city_state');
        $this->addSql('DROP TABLE county');
        $this->addSql('DROP TABLE ed_summary');
        $this->addSql('DROP TABLE enumeration');
        $this->addSql('DROP TABLE record_type');
        $this->addSql('DROP TABLE state');
        $this->addSql('DROP TABLE street');
    }
}
