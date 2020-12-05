<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201205121935 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mencho_samaya (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, uuid CHAR(36) NOT NULL --(DC2Type:uuid)
        , diary_uuid CHAR(36) NOT NULL --(DC2Type:uuid)
        , mantra_uuid CHAR(36) NOT NULL --(DC2Type:uuid)
        , count INTEGER NOT NULL, time_minutes INTEGER NOT NULL)');
        $this->addSql('DROP TABLE running');
        $this->addSql('DROP INDEX IDX_917BEDE2ABFE1C6F');
        $this->addSql('DROP INDEX UNIQ_917BEDE2ABFE1C6FE4F6001C');
        $this->addSql('CREATE TEMPORARY TABLE __temp__diary AS SELECT uuid, user_uuid, notes, noted_at FROM diary');
        $this->addSql('DROP TABLE diary');
        $this->addSql('CREATE TABLE diary (uuid CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , user_uuid CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , notes CLOB DEFAULT NULL COLLATE BINARY, noted_at DATE NOT NULL, PRIMARY KEY(uuid), CONSTRAINT FK_917BEDE2ABFE1C6F FOREIGN KEY (user_uuid) REFERENCES "user" (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO diary (uuid, user_uuid, notes, noted_at) SELECT uuid, user_uuid, notes, noted_at FROM __temp__diary');
        $this->addSql('DROP TABLE __temp__diary');
        $this->addSql('CREATE INDEX IDX_917BEDE2ABFE1C6F ON diary (user_uuid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_917BEDE2ABFE1C6FE4F6001C ON diary (user_uuid, noted_at)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE running (diary_uuid CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , distance_meters INTEGER NOT NULL, time_minutes INTEGER NOT NULL, temperature_celsius INTEGER NOT NULL, health_notes CLOB DEFAULT NULL COLLATE BINARY, party CLOB DEFAULT NULL COLLATE BINARY, PRIMARY KEY(diary_uuid))');
        $this->addSql('DROP TABLE mencho_samaya');
        $this->addSql('DROP INDEX IDX_917BEDE2ABFE1C6F');
        $this->addSql('DROP INDEX UNIQ_917BEDE2ABFE1C6FE4F6001C');
        $this->addSql('CREATE TEMPORARY TABLE __temp__diary AS SELECT uuid, user_uuid, notes, noted_at FROM diary');
        $this->addSql('DROP TABLE diary');
        $this->addSql('CREATE TABLE diary (uuid CHAR(36) NOT NULL --(DC2Type:uuid)
        , user_uuid CHAR(36) NOT NULL --(DC2Type:uuid)
        , notes CLOB DEFAULT NULL, noted_at DATE NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('INSERT INTO diary (uuid, user_uuid, notes, noted_at) SELECT uuid, user_uuid, notes, noted_at FROM __temp__diary');
        $this->addSql('DROP TABLE __temp__diary');
        $this->addSql('CREATE INDEX IDX_917BEDE2ABFE1C6F ON diary (user_uuid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_917BEDE2ABFE1C6FE4F6001C ON diary (user_uuid, noted_at)');
    }
}
