<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201205121935 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create mencho_samaya table';
    }

    public function up(Schema $schema): void
    {
        $tableMenchoSamaya = $schema->createTable('mencho_samaya');

        $tableMenchoSamaya->addColumn('uuid', 'uuid');
        $tableMenchoSamaya->setPrimaryKey(['uuid']);

        $tableMenchoSamaya->addColumn('diary_uuid', 'uuid');
        $tableMenchoSamaya->addColumn('mantra_uuid', 'uuid');
        $tableMenchoSamaya->addColumn('count', 'integer');
        $tableMenchoSamaya->addColumn('time_minutes', 'integer', ['notnull' => false]);

        $tableDiary = $schema->getTable('diary');
        $tableMenchoSamaya->addForeignKeyConstraint($tableDiary, ['diary_uuid'], ['uuid'], ['onDelete' => 'CASCADE']);

        $tableMenchoMantra = $schema->getTable('mencho_mantra');
        $tableMenchoSamaya->addForeignKeyConstraint($tableMenchoMantra, ['mantra_uuid'], ['uuid'], ['onDelete' => 'CASCADE']);

        $tableMenchoSamaya->addUniqueIndex(['diary_uuid', 'mantra_uuid']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('mencho_samaya');
    }
}
