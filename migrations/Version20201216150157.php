<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201216150157 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add swimming, water temperature and started at Running table';
    }

    public function up(Schema $schema): void
    {
        $schema->getTable('running')->addColumn('is_swam', 'boolean', ['notnull' => false]);
        $schema->getTable('running')->addColumn('water_temperature_celsius', 'integer', ['notnull' => false]);
        $schema->getTable('running')->addColumn('started_at', 'datetime', ['notnull' => false]);
    }

    public function down(Schema $schema): void
    {
        $schema->getTable('running')->dropColumn('is_swam');
        $schema->getTable('running')->dropColumn('water_temperature_celsius');
        $schema->getTable('running')->dropColumn('started_at');
    }
}
