<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateReportsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('reports');
        $table->addColumn('user_id', 'integer')
            ->addColumn('product_id', 'integer', ['null' => true])
            ->addColumn('seller_id', 'integer', ['null' => true])
            ->addColumn('reason', 'text')
            ->addColumn('status', 'string', ['limit' => 50, 'default' => 'pending']) // pending, reviewed, resolved
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['null' => true])

            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('product_id', 'products', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('seller_id', 'users', 'id', ['delete' => 'CASCADE'])
            ->create();
    }
}