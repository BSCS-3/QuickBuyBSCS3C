<?php
use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
{
    public function run(): void
    {
        $data = [
            [
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'first_name' => 'Admin',
                'last_name' => 'User',
                'is_active' => true,
                'role' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => null
            ],
            [
                'username' => 'john_doe',
                'email' => 'john@example.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'first_name' => 'John',
                'last_name' => 'Doe',
                'is_active' => true,
                'role' => 'customer',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => null
            ]
        ];

        $this->table('users')->insert($data)->save();
    }
}