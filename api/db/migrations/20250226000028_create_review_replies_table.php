<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateReviewRepliesTable extends AbstractMigration
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
        $table = $this->table('review_replies');
        $table->addColumn('review_id', 'integer', ['signed' => false])
            ->addColumn('seller_id', 'integer', ['signed' => false])
            ->addColumn('comment', 'text')
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['null' => true])

            ->addForeignKey('review_id', 'reviews', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION',
                'constraint' => 'fk_review_replies_review'
            ])
            ->addForeignKey('seller_id', 'users', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION',
                'constraint' => 'fk_review_replies_seller'
            ])
            ->create();
    }
}
