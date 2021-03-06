<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201217051531 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create sleeping table';
    }

    public function up(Schema $schema): void
    {
        $tableSleeping = $schema->createTable('sleeping');
        $tableSleeping->addColumn('diary_uuid', 'uuid');
        $tableSleeping->setPrimaryKey(['diary_uuid']);
        $tableSleeping->addColumn('awake_at', 'time', ['notnull' => false]);
        $tableSleeping->addColumn('sleep_at', 'time', ['notnull' => false]);

        $tableDiary = $schema->getTable('diary');
        $tableSleeping->addForeignKeyConstraint($tableDiary, ['diary_uuid'], ['uuid'], ['onDelete' => 'CASCADE']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('sleeping');
    }

    // Fix: https://github.com/doctrine/migrations/issues/1104
    public function isTransactional(): bool
    {
        return false;
    }
}
