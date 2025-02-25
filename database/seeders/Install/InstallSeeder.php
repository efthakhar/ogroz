<?php

namespace Database\Seeders\Install;

use App\Models\Setting\User;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class InstallSeeder extends Seeder
{
    public function run(): void
    {
        try {
            Cache::flush();
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            // Seed Super Admin
            DB::table('users')->truncate();
            $this->seedSuperAdmin();


            // Seed Permissions
            DB::table('model_has_permissions')->truncate();
            DB::table('model_has_roles')->truncate();
            DB::table('permissions')->truncate();
            DB::table('roles')->truncate();
            DB::table('role_has_permissions')->truncate();
            $permissions = include_once(base_path('database/data/config/permissions.php'));
            foreach ($permissions as $permission) {
                Permission::create(['name' => $permission]);
            }


            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        } catch (Exception $e) {
            DB::rollback();
            report($e);
            throw $e;
        }
    }


    public function seedSuperAdmin()
    {
        return User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@ogroz.com',
            'password' => Hash::make('superadmin@ogroz.com'),
        ]);
    }
}

// php artisan db:seed --class="Database\Seeders\Install\InstallSeeder"
