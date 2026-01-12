<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240101000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create shipping_orders table with custom types for ID and Weight';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE shipping_orders (id VARCHAR(255) NOT NULL, origin VARCHAR(255) NOT NULL, destination VARCHAR(255) NOT NULL, weight NUMERIC(10, 2) NOT NULL, sender_email VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN shipping_orders.id IS \'(DC2Type:shipping_id)\'');
        $this->addSql('COMMENT ON COLUMN shipping_orders.weight IS \'(DC2Type:weight)\'');
        $this->addSql('COMMENT ON COLUMN shipping_orders.created_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE shipping_orders');
    }
}
