<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCouponsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('coupons');
        $table->addColumn('seller_id', 'integer')
            ->addColumn('code', 'string', ['limit' => 50])
            ->addColumn('discount_amount', 'decimal', ['precision' => 10, 'scale' => 2])
            ->addColumn('is_percentage', 'boolean', ['default' => false])
            ->addColumn('valid_from', 'datetime')
            ->addColumn('valid_until', 'datetime', ['null' => true])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['null' => true])

            ->addForeignKey('seller_id', 'users', 'id', ['delete' => 'CASCADE'])
            ->addIndex(['code'], ['unique' => true])
            ->create();
    }
}