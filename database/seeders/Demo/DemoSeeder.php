<?php

namespace Database\Seeders\Demo;

use App\Models\Accounting\AccountGroup;
use App\Models\Setting\User;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
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

            Artisan::call('migrate:fresh');

            Cache::flush();

            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            // Seed Permissions
            $this->seedPermissions();

            // Seed Super Admin
            $this->seedSuperAdmin();

            //seed system configurations
            $this->seedSystemConfigurations();

            //seed account groups
            $this->accountGroupSeeder();

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

    public function accountGroupSeeder()
    {
        DB::table('account_groups')->truncate();

        // Asset Type Accounts
        $bankAccounts = AccountGroup::create([
            'name' => 'Bank Accounts',
            'type' => 'asset',
            'parent_account_group_id' => NULL,
        ]);

        $CashInHand = AccountGroup::create([
            'name' => 'Cash-in-hand',
            'type' => 'asset',
            'parent_account_group_id' => NULL,
        ]);

        $currentAssets = AccountGroup::create([
            'name' => 'Current Assets',
            'type' => 'asset',
            'parent_account_group_id' => NULL,
        ]);

        $sundryDebtors = AccountGroup::create([
            'name' => 'Sundry Debtors',
            'type' => 'asset',
            'parent_account_group_id' =>  $currentAssets->id,
        ]);

        $stockInHand = AccountGroup::create([
            'name' => 'Stock-in-hand',
            'type' => 'asset',
            'parent_account_group_id' =>  $currentAssets->id,
        ]);

        $loansAndAdvancesAsset = AccountGroup::create([
            'name' => 'Loans and Advances (Asset)',
            'type' => 'asset',
            'parent_account_group_id' =>  $currentAssets->id,
        ]);

        $depositsAsset = AccountGroup::create([
            'name' => 'Deposits (Asset)',
            'type' => 'asset',
            'parent_account_group_id' =>  $currentAssets->id,
        ]);

        $fixedAssets = AccountGroup::create([
            'name' => 'Fixed Assets',
            'type' => 'asset',
            'parent_account_group_id' =>  NULL,
        ]);

        $land = AccountGroup::create([
            'name' => 'Land',
            'type' => 'asset',
            'parent_account_group_id' => $fixedAssets->id,
        ]);

        $buildings = AccountGroup::create([
            'name' => 'Buildings',
            'type' => 'asset',
            'parent_account_group_id' => $fixedAssets->id,
        ]);

        $plantAndMachinery = AccountGroup::create([
            'name' => 'Plant & Machinery',
            'type' => 'asset',
            'parent_account_group_id' => $fixedAssets->id,
        ]);

        $furnitureAndFixtures = AccountGroup::create([
            'name' => 'Furniture & Fixtures',
            'type' => 'asset',
            'parent_account_group_id' => $fixedAssets->id,
        ]);

        $vehicles = AccountGroup::create([
            'name' => 'Vehicles',
            'type' => 'asset',
            'parent_account_group_id' => $fixedAssets->id,
        ]);

        $computers = AccountGroup::create([
            'name' => 'Computers',
            'type' => 'asset',
            'parent_account_group_id' => $fixedAssets->id,
        ]);

        $investments = AccountGroup::create([
            'name' => 'Invenstments',
            'type' => 'asset',
            'parent_account_group_id' => NULL,
        ]);

        // Liability Types Groups
        $bankODAc = AccountGroup::create([
            'name' => 'Bank OD A/c',
            'type' => 'liability',
            'parent_account_group_id' => NULL,
        ]);

        $currentLiabilities = AccountGroup::create([
            'name' => 'Current Liabilities',
            'type' => 'liability',
            'parent_account_group_id' => NULL,
        ]);

        $sundryCreditors = AccountGroup::create([
            'name' => 'Sundry Creditors',
            'type' => 'liability',
            'parent_account_group_id' => $currentLiabilities->id,
        ]);

        $provisions = AccountGroup::create([
            'name' => 'Provisions',
            'type' => 'liability',
            'parent_account_group_id' => $currentLiabilities->id,
        ]);
        
        $dutiesAndTaxes = AccountGroup::create([
            'name' => 'Duties & Taxes',
            'type' => 'liability',
            'parent_account_group_id' => $currentLiabilities->id,
        ]);

        $loansLiability = AccountGroup::create([
            'name' => 'Loans (Liability)',
            'type' => 'liability',
            'parent_account_group_id' => NULL,
        ]);

        $securedLoans = AccountGroup::create([
            'name' => 'Secured Loans',
            'type' => 'liability',
            'parent_account_group_id' => $loansLiability->id,
        ]);

        $unsecuredLoans = AccountGroup::create([
            'name' => 'Unsecured Loans',
            'type' => 'liability',
            'parent_account_group_id' => $loansLiability->id,
        ]);

        //Income Type Groups
        $directIncomes = AccountGroup::create([
            'name' => 'Direct Incomes',
            'type' => 'income',
            'parent_account_group_id' => NULL,
        ]);

        $indirectIncomes = AccountGroup::create([
            'name' => 'Indirect Incomes',
            'type' => 'income',
            'parent_account_group_id' => NULL,
        ]);

        $salesAccounts = AccountGroup::create([
            'name' => 'Sales Accounts',
            'type' => 'income',
            'parent_account_group_id' => NULL,
        ]);

        // Expense Account Groups
        $directExpenses = AccountGroup::create([
            'name' => 'Direct Expenses',
            'type' => 'expense',
            'parent_account_group_id' => NULL,
        ]);

        $indirectExpenses = AccountGroup::create([
            'name' => 'Indirect Expenses',
            'type' => 'expense',
            'parent_account_group_id' => NULL,
        ]);

        $purchaseAccounts = AccountGroup::create([
            'name' => 'Purchase Accounts',
            'type' => 'expense',
            'parent_account_group_id' => NULL,
        ]);

        // Equity Group Type
        $capitalAccount = AccountGroup::create([
            'name' => 'Capital Account',
            'type' => 'equity',
            'parent_account_group_id' => NULL,
        ]);

        $reservesAndSurplus = AccountGroup::create([
            'name' => 'Reserves & Surplus',
            'type' => 'equity',
            'parent_account_group_id' => NULL,
        ]);
    }
}



// php artisan db:seed --class="Database\Seeders\Demo\DemoSeeder"
