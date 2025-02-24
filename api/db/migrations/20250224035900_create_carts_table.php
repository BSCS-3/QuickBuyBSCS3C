<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCartsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('carts');
        $table->addColumn('user_id', 'integer')
            ->addColumn('product_id', 'integer')
            ->addColumn('quantity', 'integer')
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['null' => true])

            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('product_id', 'products', 'id', ['delete' => 'CASCADE'])
            ->create();
    }
}