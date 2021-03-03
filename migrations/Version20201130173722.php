<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201130173722 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create mencho_mantra table';
    }

    public function up(Schema $schema): void
    {
        $tableMenchoMantra = $schema->createTable('mencho_mantra');
        $tableMenchoMantra->addColumn('uuid', 'uuid');
        $tableMenchoMantra->setPrimaryKey(['uuid']);

        $tableMenchoMantra->addColumn('name', 'string');
        $tableMenchoMantra->addUniqueIndex(['name']);

        $tableMenchoMantra->addColumn('level', 'integer');
        $tableMenchoMantra->addColumn('description', 'text', ['notnull' => false]);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('mencho_mantra');
    }

    // Fix: https://github.com/doctrine/migrations/issues/1104
    public function isTransactional(): bool
    {
        return false;
    }
}
