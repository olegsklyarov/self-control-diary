<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201130123348 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create diary table';
    }

    public function up(Schema $schema): void
    {
        $tableDiary = $schema->createTable('diary');
        $tableDiary->addColumn('uuid', 'uuid');
        $tableDiary->addColumn('notes', 'text', ['notnull' => false]);
        $tableDiary->addColumn('noted_at', 'date');
        $tableDiary->addColumn('user_uuid', 'uuid');
        $tableDiary->setPrimaryKey(['uuid']);
        $tableDiary->addUniqueIndex(['user_uuid', 'noted_at']);

        $tableUser = $schema->getTable('user');
        $tableDiary->addForeignKeyConstraint($tableUser, ['user_uuid'], ['uuid']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('diary');
    }
}
