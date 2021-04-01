<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210401065956 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create lead table';
    }

    public function up(Schema $schema): void
    {
        $tableLead = $schema->createTable('lead');
        $tableLead->addColumn('uuid', 'uuid');
        $tableLead->addColumn('email', 'string');
        $tableLead->addColumn('password_hash', 'string');
        $tableLead->addColumn('created_at', 'datetime');
        $tableLead->addColumn('verified_email_at', 'datetime', ['notnull' => false]);
        $tableLead->addColumn('verification_email_sent_at', 'datetime', ['notnull' => false]);
        $tableLead->addColumn('verification_token', 'string', ['notnull' => false]);
        $tableLead->setPrimaryKey(['uuid']);
        $tableLead->addUniqueIndex(['email']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('lead');
    }

    // Fix: https://github.com/doctrine/migrations/issues/1104
    public function isTransactional(): bool
    {
        return false;
    }
}
