<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateReviewRepliesTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('review_replies');
        $table->addColumn('review_id', 'integer')
            ->addColumn('seller_id', 'integer')
            ->addColumn('comment', 'text')
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['null' => true])

            ->addForeignKey('review_id', 'reviews', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('seller_id', 'users', 'id', ['delete' => 'CASCADE'])
            ->create();
    }
}