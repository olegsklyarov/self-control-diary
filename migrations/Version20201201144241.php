<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201201144241 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add user roles';
    }

    public function up(Schema $schema) : void
    {
        $schema->getTable('user')->addColumn('roles', 'json', ['default' => '[]']);
    }

    public function down(Schema $schema) : void
    {
        $schema->getTable('user')->dropColumn('roles');
    }
}
