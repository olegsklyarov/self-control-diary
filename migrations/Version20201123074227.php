<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201123074227 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create user table';
    }

    public function up(Schema $schema) : void
    {
        $tableUser = $schema->createTable('user');
        $tableUser->addColumn('uuid', 'guid');
        $tableUser->addColumn('email', 'string');
        $tableUser->addColumn('password_hash', 'string', ['notnull' => false]);
        $tableUser->addColumn('created_at', 'datetime');
        $tableUser->addColumn('last_visit_at', 'datetime', ['notnull' => false]);
        $tableUser->setPrimaryKey(['uuid']);
        $tableUser->addUniqueIndex(['email']);
    }

    public function down(Schema $schema) : void
    {
        $schema->dropTable('user');
    }
}
