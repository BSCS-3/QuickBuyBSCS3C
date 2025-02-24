<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateProductCategoriesTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('product_categories');
        $table->addColumn('product_id', 'integer')
            ->addColumn('category_id', 'integer')

            ->addForeignKey('product_id', 'products', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('category_id', 'categories', 'id', ['delete' => 'CASCADE'])
            ->create();
    }
}