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
        $table = $this->table('users');
        $table->addColumn('username', 'string', ['limit' => 255])
            ->addColumn('email', 'string', ['limit' => 255])
            ->addColumn('password', 'string', ['limit' => 255])
            ->addColumn('first_name', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('last_name', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('is_active', 'boolean', ['default' => true])
            ->addColumn('role', 'string', ['limit' => 50, 'default' => 'user'])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['null' => true])

            ->addIndex(['email'], ['unique' => true])
            ->addIndex(['username'], ['unique' => true])

            ->create();
    }
}
