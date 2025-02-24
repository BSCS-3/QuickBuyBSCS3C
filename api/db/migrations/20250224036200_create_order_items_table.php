<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateOrderItemsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('order_items');
        $table->addColumn('order_id', 'integer')
            ->addColumn('product_id', 'integer')
            ->addColumn('quantity', 'integer')
            ->addColumn('price', 'decimal', ['precision' => 10, 'scale' => 2])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])

            ->addForeignKey('order_id', 'orders', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('product_id', 'products', 'id', ['delete' => 'CASCADE'])
            ->create();
    }
}