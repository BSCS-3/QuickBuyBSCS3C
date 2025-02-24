<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateWishlistsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('wishlists');
        $table->addColumn('user_id', 'integer')
            ->addColumn('product_id', 'integer')
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])

            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('product_id', 'products', 'id', ['delete' => 'CASCADE'])
            ->create();
    }
}