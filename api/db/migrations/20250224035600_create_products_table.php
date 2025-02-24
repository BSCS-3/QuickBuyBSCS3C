<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateProductsTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table('products');
        $table->addColumn('seller_id', 'integer')
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('description', 'text')
            ->addColumn('price', 'decimal', ['precision' => 10, 'scale' => 2])
            ->addColumn('stock', 'integer')
            ->addColumn('status', 'string', ['limit' => 50, 'default' => 'active']) // active, inactive, deleted
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['null' => true])

            ->addForeignKey('seller_id', 'users', 'id', ['delete' => 'CASCADE'])
            ->create();
    }
}
