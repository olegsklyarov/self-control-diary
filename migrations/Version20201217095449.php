<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201217095449 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Change datetime to time where its necessary';
    }

    public function up(Schema $schema) : void
    {
        $schema->getTable('running')
            ->dropColumn('started_at')
            ->addColumn('started_at', 'time', ['notnull' => false]);

        $schema->getTable('sleeping')
            ->dropColumn('awake_at')
            ->addColumn('awake_at', 'time', ['notnull' => false]);

        $schema->getTable('sleeping')
            ->dropColumn('sleep_at')
            ->addColumn('sleep_at', 'time', ['notnull' => false]);
    }

    public function down(Schema $schema) : void
    {
        $schema->getTable('running')
            ->dropColumn('started_at')
            ->addColumn('started_at', 'datetime', ['notnull' => false]);

        $schema->getTable('sleeping')
            ->dropColumn('awake_at')
            ->addColumn('awake_at', 'datetime', ['notnull' => false]);

        $schema->getTable('sleeping')
            ->dropColumn('sleep_at')
            ->addColumn('sleep_at', 'datetime', ['notnull' => false]);
    }
}
