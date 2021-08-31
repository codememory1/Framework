<?php

namespace Migrations;

use Codememory\Components\Database\Migrations\AbstractMigration;
use Codememory\Components\Database\Migrations\Interfaces\SchemaInterface as MigrationSchemaInterface;

/**
 * Class Migration1628435335CreateProductTable
 *
 * @package Migrations
 */
final class Migration1628435335CreateProductTable extends AbstractMigration
{

    /**
     * @inheritDoc
     */
    public function up(MigrationSchemaInterface $schema): void
    {

        $schema->addSql('CREATE TABLE IF NOT EXISTS `products` (`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,`name` VARCHAR(100) NOT NULL,`desc` VARCHAR(500) NULL,`amount` DECIMAL(10) NOT NULL)');

    }

    /**
     * @inheritDoc
     */
    public function down(MigrationSchemaInterface $schema): void
    {

        $schema->addSql('DROP TABLE IF EXISTS `products`');

    }

}