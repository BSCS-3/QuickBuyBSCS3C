<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateBusinessProfiles extends AbstractMigration
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
        $table = $this->table('business_profiles');
        $table->addColumn('seller_id', 'integer', ['signed' => false])
            ->addColumn('business_name', 'string', ['limit' => 255])
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('logo_url', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('contact_email', 'string', ['limit' => 255])
            ->addColumn('contact_phone', 'string', ['limit' => 50, 'null' => true])
            ->addColumn('address', 'text', ['null' => true])
            ->addColumn('is_approved', 'boolean', ['default' => false])
            ->addColumn('approval_date', 'datetime', ['null' => true])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['null' => true])

            ->addForeignKey('seller_id', 'users', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION',
                'constraint' => 'fk_business_profiles_seller'
            ])
            ->addIndex(['business_name'], ['unique' => true])
            ->create();
    }
}
