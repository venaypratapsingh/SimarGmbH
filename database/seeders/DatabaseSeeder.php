<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Hash;
use Spatie\Permission\Traits\HasRoles;
use DB;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@simargmbh.com'],
            [
                'name' => 'Admin',
                'password' => '$2y$10$jq2BtYq2.eg7tNgdXqKvye.1kiximVyOiH1MC4ZlmTE9NqZAhEWmq',
            ]
        );

        $role = Role::firstOrCreate(
            ['slug' => 'admin'],
            ['name' => 'Adminstrator']
        );
        $user->roles()->syncWithoutDetaching($role->id);

        Role::firstOrCreate(
            ['slug' => 'employee'],
            ['name' => 'Employee']
        );
    }
}
