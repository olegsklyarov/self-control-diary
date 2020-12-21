<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201214171147 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add refresh tokens table (JWTRefreshTokenBundle)';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('refresh_tokens');
        $table->addColumn('id', 'integer');
        $table->setPrimaryKey(['id']);
        $table->addColumn('refresh_token', 'string', ['length' => 128]);
        $table->addColumn('username', 'string');
        $table->addColumn('valid', 'datetime');
        $table->addUniqueIndex(['refresh_token']);
        $schema->createSequence('refresh_tokens_id_seq');
    }

    public function down(Schema $schema): void
    {
        $schema->dropSequence('refresh_tokens_id_seq');
        $schema->dropTable('refresh_tokens');
    }
}
