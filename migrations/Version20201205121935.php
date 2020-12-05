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
        return 'Create mencho_samaya table';
    }

    public function up(Schema $schema) : void
    {
        $tableRunning = $schema->createTable('mencho_samaya');

        $tableRunning->addColumn('uuid', 'uuid');
        $tableRunning->setPrimaryKey(['uuid']);

        $tableRunning->addColumn('diary_uuid', 'uuid');
        $tableRunning->addColumn('mantra_uuid', 'uuid');
        $tableRunning->addColumn('count', 'integer');
        $tableRunning->addColumn('time_minutes', 'integer');

        $tableDiary = $schema->getTable('diary');
        $tableRunning->addForeignKeyConstraint($tableDiary, ['diary_uuid'], ['uuid']);

        $tableMenchoMantra = $schema->getTable('mencho_mantra');
        $tableRunning->addForeignKeyConstraint($tableMenchoMantra, ['mantra_uuid'], ['uuid']);
    }

    public function down(Schema $schema) : void
    {
        $schema->dropTable('mencho_samaya');
    }
}
