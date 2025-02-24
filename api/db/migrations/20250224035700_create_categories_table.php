<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCategoriesTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('categories');
        $table->addColumn('seller_id', 'integer')
            ->addColumn('name', 'string', ['limit' => 100])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['null' => true])

            ->addForeignKey('seller_id', 'users', 'id', ['delete' => 'CASCADE'])
            ->create();
    }
}