<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateReportsTable extends AbstractMigration
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
        $table = $this->table('reports');
        $table->addColumn('user_id', 'integer', ['signed' => false])
            ->addColumn('product_id', 'integer', ['signed' => false, 'null' => true])
            ->addColumn('seller_id', 'integer', ['signed' => false, 'null' => true])
            ->addColumn('reason', 'text')
            ->addColumn('status', 'string', ['limit' => 50, 'default' => 'pending']) // pending, reviewed, resolved
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['null' => true])

            ->addForeignKey('user_id', 'users', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION',
                'constraint' => 'fk_reports_user'
            ])
            ->addForeignKey('product_id', 'products', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION',
                'constraint' => 'fk_reports_product'
            ])
            ->addForeignKey('seller_id', 'users', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION',
                'constraint' => 'fk_reports_seller'
            ])
            ->create();
    }
}
