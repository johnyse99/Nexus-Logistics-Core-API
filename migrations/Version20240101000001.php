<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240101000001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add carrier column to shipping_orders';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE shipping_orders ADD carrier VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE shipping_orders DROP carrier');
    }
}
