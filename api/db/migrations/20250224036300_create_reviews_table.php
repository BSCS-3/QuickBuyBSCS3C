<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateReviewsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('reviews');
        $table->addColumn('user_id', 'integer')
            ->addColumn('product_id', 'integer', ['null' => true])
            ->addColumn('seller_id', 'integer', ['null' => true])
            ->addColumn('rating', 'integer')
            ->addColumn('comment', 'text')
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['null' => true])

            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('product_id', 'products', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('seller_id', 'users', 'id', ['delete' => 'CASCADE'])
            ->create();
    }
}