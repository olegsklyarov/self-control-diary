<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201216150157 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add isSwam (boolean, optional), waterTemperatureCelsius (integer, optional), startedAt (datetime) at Running table';
    }

    public function up(Schema $schema): void
    {
        $schema->getTable('running')->addColumn('isSwam', 'boolean');
        $schema->getTable('running')->addColumn('waterTemperatureCelsius', 'integer');
        $schema->getTable('running')->addColumn('startedAt', 'integer');
    }

    public function down(Schema $schema): void
    {
        $schema->getTable('running')->dropColumn('isSwam');
        $schema->getTable('running')->dropColumn('waterTemperatureCelsius');
        $schema->getTable('running')->dropColumn('startedAt');
    }
}
