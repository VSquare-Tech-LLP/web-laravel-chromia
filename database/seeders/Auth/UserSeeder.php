<?php

namespace Database\Seeders\Auth;

use App\Domains\Auth\Models\User;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

/**
 * Class UserTableSeeder.
 */
class UserSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();


        $expected_domain = env('APP_EXPECTED_DOMAIN', 'admin.com');
        $expected_password = env('APP_USER_PASSWORD','secret');

        // Add the master administrator, user id of 1
        User::create([
            'type' => User::TYPE_ADMIN,
            'username' => 'superadmin',
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'password' => 'secret',
            'email_verified_at' => now(),
            'active' => true,
        ]);

        User::create([
            'type' => User::TYPE_ADMIN,
            'name' => 'Super Author',
            'username' => 'superauthor',
            'email' => 'superauthor@' . $expected_domain,
            'password' => $expected_password,
            'email_verified_at' => now(),
            'active' => true,
        ]);

        User::create([
            'type' => User::TYPE_ADMIN,
            'name' => 'Author',
            'username' => 'author',
            'email' => 'author@' . $expected_domain,
            'password' => $expected_password,
            'email_verified_at' => now(),
            'active' => true,
        ]);

        if (app()->environment(['local', 'testing'])) {
            User::create([
                'type' => User::TYPE_USER,
                'username' => 'testuser',
                'name' => 'Test User',
                'email' => 'user@user.com',
                'password' => 'secret',
                'email_verified_at' => now(),
                'active' => true,
            ]);
        }

        $this->enableForeignKeys();
    }
}
