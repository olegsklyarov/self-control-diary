<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201201163127 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add user api token and expiration';
    }

    public function up(Schema $schema) : void
    {
        $tableUser = $schema->getTable('user');
        $tableUser->addColumn('api_token', 'string', ['notnull' => false]);
        $tableUser->addColumn('api_token_expires_at', 'datetime', ['notnull' => false]);
    }

    public function down(Schema $schema) : void
    {
        $tableUser = $schema->getTable('user');
        $tableUser->dropColumn('api_token');
        $tableUser->dropColumn('api_token_expires_at');
    }
}
