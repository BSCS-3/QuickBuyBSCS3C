<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCouponsTable extends AbstractMigration
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
        $table = $this->table('coupons');
        $table->addColumn('seller_id', 'integer', ['signed' => false])
            ->addColumn('code', 'string', ['limit' => 50])
            ->addColumn('discount_amount', 'decimal', ['precision' => 10, 'scale' => 2])
            ->addColumn('is_percentage', 'boolean', ['default' => false])
            ->addColumn('valid_from', 'datetime')
            ->addColumn('valid_until', 'datetime', ['null' => true])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['null' => true])

            ->addForeignKey('seller_id', 'users', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION',
                'constraint' => 'fk_coupons_seller'
            ])
            ->addIndex(['code'], ['unique' => true])
            ->create();
    }
}
