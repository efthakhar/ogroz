<?php

namespace Database\Seeders\Demo;

use App\Models\Setting\User;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        try {
            Cache::flush();
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            // Seed Permissions
            $this->seedPermissions();

            // Seed Super Admin
            $this->seedSuperAdmin();

            //seed system configurations
            $this->seedSystemConfigurations();

            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        } catch (Exception $e) {
            report($e);
            throw $e;
        }
    }


    public function seedPermissions()
    {
        DB::table('model_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();
        DB::table('role_has_permissions')->truncate();
        $permissionGroups = include_once(base_path('database/data/config/permissions.php'));
        foreach ($permissionGroups as $permissionGroup) {
            foreach ($permissionGroup as $permission) {
                Permission::create(['name' => $permission]);
            }
        }
    }

    public function seedSuperAdmin()
    {
        DB::table('users')->truncate();
        Role::create(['name' => 'Super Admin']);
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@ogroz.com',
            'active' => 1,
            'password' => Hash::make('superadmin@ogroz.com'),
        ]);
        $user->assignRole('Super Admin');
    }

    public function seedSystemConfigurations()
    {
        DB::table('options')
            ->whereIn('key', [
                'company_name',
                'company_phone_no',
                'company_email',
                'company_address'
            ])
            ->delete();

        setOption('system_configurations', [
            'company_name' => "Hexagon Trading",
            'company_phone_no' => 894829029,
            'company_email' => 'hexagontrading@yahoo.com',
            'company_address' => 'East Nasirabard, House No. 354, GEC, Chittagong',
        ]);
    }
}

// php artisan db:seed --class="Database\Seeders\Demo\DemoSeeder"
