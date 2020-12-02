<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201202063912 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create running table';
    }

    public function up(Schema $schema) : void
    {
        $tableRunning = $schema->createTable('running');

        $tableRunning->addColumn('diary_uuid', 'uuid');
        $tableRunning->setPrimaryKey(['diary_uuid']);

        $tableRunning->addColumn('distance_meters', 'integer');
        $tableRunning->addColumn('time_minutes', 'integer');
        $tableRunning->addColumn('temperature_celsius', 'integer');
        $tableRunning->addColumn('health_notes', 'text', ['notnull' => false]);
        $tableRunning->addColumn('party', 'text', ['notnull' => false]);

        $tableDiary = $schema->getTable('diary');
        $tableDiary->addForeignKeyConstraint($tableDiary, ['diary_uuid'], ['uuid']);
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $schema->dropTable('running');
    }
}
