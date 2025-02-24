<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateOrdersTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('orders');
        $table->addColumn('user_id', 'integer')
            ->addColumn('total_amount', 'decimal', ['precision' => 10, 'scale' => 2])
            ->addColumn('status', 'string', ['limit' => 50]) // pending, approved, delivered, cancelled
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['null' => true])

            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE'])
            ->create();
    }
}