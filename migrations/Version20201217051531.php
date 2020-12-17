<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201217051531 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create sleeping table';
    }

    public function up(Schema $schema) : void
    {
        $tableSleeping = $schema->createTable('sleeping');
        $tableSleeping->addColumn('diary_uuid', 'uuid');
        $tableSleeping->setPrimaryKey(['diary_uuid']);
        $tableSleeping->addColumn('awake_at', 'datetime', ['notnull' => false]);
        $tableSleeping->addColumn('sleep_at', 'datetime', ['notnull' => false]);

        $tableDiary = $schema->getTable('diary');
        $tableSleeping->addForeignKeyConstraint($tableDiary, ['diary_uuid'], ['uuid']);
    }

    public function down(Schema $schema) : void
    {
        $schema->dropTable('sleeping');
    }
}
