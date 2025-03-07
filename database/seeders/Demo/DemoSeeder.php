<?php

namespace Database\Seeders\Demo;

use App\Models\Accounting\Account;
use App\Models\Accounting\AccountGroup;
use App\Models\Accounting\JournalEntry;
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

            //seed journal entries
            $this->journalEntrySeeder();

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
        DB::table('accounts')->truncate();

        // Asset Type Accounts
        $assets = AccountGroup::create([
            'name' => 'Assets',
            'type' => 'asset',
            'parent_account_group_id' => null,
        ]);

        $currentAssets = AccountGroup::create([
            'name' => 'Current Assets',
            'type' => 'asset',
            'parent_account_group_id' => $assets->id,
        ]);

        $bankAccounts = AccountGroup::create([
            'name' => 'Bank Accounts',
            'type' => 'asset',
            'parent_account_group_id' => $currentAssets->id,
        ]);

        $bankAccounts->accounts()->createMany([
            [
                'name' => 'ABC Bank - 7920011',
                'number' => '101-001',
            ],
            [
                'name' => 'Jamuna Bank - 9032314',
                'number' => '101-002',
            ],
            [
                'name' => 'Rupali Bank - 5001300',
                'number' => '101-003',
            ],
            [
                'name' => 'Dhaka Bank - 5001300',
                'number' => '101-004',
            ],
        ]);

        $cashInHand = AccountGroup::create([
            'name' => 'Cash-in-hand',
            'type' => 'asset',
            'parent_account_group_id' => $currentAssets->id,
        ]);

        $cashInHand->accounts()->createMany([
            [
                'name' => 'Main Cash',
                'number' => '102-001',
            ],
            [
                'name' => 'Petty Cash',
                'number' => '102-002',
            ],
            [
                'name' => 'GEC Branch Cash',
                'number' => '102-003',
            ],
            [
                'name' => 'Sales Counter Cash',
                'number' => '102-004',
            ],
        ]);

        $sundryDebtors = AccountGroup::create([
            'name' => 'Accounts Receivable (Debtors)',
            'type' => 'asset',
            'parent_account_group_id' => $currentAssets->id,
        ]);

        $sundryDebtors->accounts()->createMany([
            [
                'name' => 'Jacky Chaan',
                'number' => '103-001',
            ],
            [
                'name' => 'Maurise Brown',
                'number' => '103-002',
            ],
            [
                'name' => 'Oliva Bowman',
                'number' => '103-003',
            ],
            [
                'name' => 'Jemmy Henry',
                'number' => '103-004',
            ],
            [
                'name' => 'Devid Javi',
                'number' => '103-005',
            ],
            [
                'name' => 'Mrs Melinda',
                'number' => '103-006',
            ],
            [
                'name' => 'J&K Restaurant',
                'number' => '103-007',
            ],
        ]);

        $stockInHand = AccountGroup::create([
            'name' => 'Inventory',
            'type' => 'asset',
            'parent_account_group_id' => $currentAssets->id,
        ]);

        $stockInHand->accounts()->createMany([
            ['name' => 'Inventory - Grocery Items', 'number' => '104-001'],
            ['name' => 'Inventory - Packaged Food & Beverages', 'number' => '104-002'],
            ['name' => 'Inventory - Health & Beauty Products', 'number' => '104-003'],
            ['name' => 'Inventory - Electronic Accessories', 'number' => '104-004'],
            ['name' => 'Inventory - Miscellaneous Items', 'number' => '104-005'],
        ]);

        $loansAndAdvancesAsset = AccountGroup::create([
            'name' => 'Loans and Advances (Asset)',
            'type' => 'asset',
            'parent_account_group_id' => $currentAssets->id,
        ]);

        $depositsAsset = AccountGroup::create([
            'name' => 'Deposits (Asset)',
            'type' => 'asset',
            'parent_account_group_id' => $currentAssets->id,
        ]);

        $fixedAssets = AccountGroup::create([
            'name' => 'Fixed Assets',
            'type' => 'asset',
            'parent_account_group_id' => $assets->id,
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
            'name' => 'Investments',
            'type' => 'asset',
            'parent_account_group_id' => $assets->id,
        ]);

        // Expense Account Groups
        $expenses = AccountGroup::create([
            'name' => 'Expenses',
            'type' => 'expense',
            'parent_account_group_id' => null,
        ]);

        $directExpenses = AccountGroup::create([
            'name' => 'Direct Expenses',
            'type' => 'expense',
            'parent_account_group_id' => $expenses->id,
        ]);

        $purchaseSubGroup = AccountGroup::create([
            'name' => 'Purchase Accounts',
            'type' => 'expense',
            'parent_account_group_id' => $directExpenses->id,
        ]);

        $purchaseSubGroup->accounts()->createMany([
            ['name' => 'Purchases - Grocery Items', 'number' => '501-004'],
            ['name' => 'Purchases - Packaged Food & Beverages', 'number' => '501-005'],
            ['name' => 'Purchases - Health & Beauty Products', 'number' => '501-006'],
            ['name' => 'Purchases - Electronic Accessories', 'number' => '501-007'],
            ['name' => 'Purchases - Miscellaneous Items', 'number' => '501-008'],
            ['name' => 'Purchase Returns (Contra)', 'number' => '501-009'],
        ]);

        $directExpenses->accounts()->createMany([
            ['name' => 'Cost of Goods Sold (COGS)', 'number' => '501-001'],
            ['name' => 'Freight Inward', 'number' => '501-002'],
            ['name' => 'Direct Labor Costs', 'number' => '501-003'],
        ]);

        $indirectExpenses = AccountGroup::create([
            'name' => 'Indirect Expenses',
            'type' => 'expense',
            'parent_account_group_id' => $expenses->id,
        ]);

        $indirectExpenses->accounts()->createMany([
            ['name' => 'Rent & Utilities', 'number' => '502-001'],
            ['name' => 'Electricity Expense', 'number' => '502-002'],
            ['name' => 'Water & Gas Expenses', 'number' => '502-003'],
            ['name' => 'Internet & Telephone Expenses', 'number' => '502-004'],
            ['name' => 'Salaries & Wages', 'number' => '502-005'],
            ['name' => 'Marketing & Advertisement', 'number' => '502-006'],
            ['name' => 'Office Supplies', 'number' => '502-007'],
            ['name' => 'Depreciation - Fixtures & Equipment', 'number' => '502-008'],
            ['name' => 'Bank Charges', 'number' => '502-009'],
            ['name' => 'Miscellaneous Expenses', 'number' => '502-010'],
        ]);



        // Liability Type Groups
        $liabilities = AccountGroup::create([
            'name' => 'Liabilities',
            'type' => 'liability',
            'parent_account_group_id' => null,
        ]);

        $bankODAc = AccountGroup::create([
            'name' => 'Bank Overdraft',
            'type' => 'liability',
            'parent_account_group_id' => $liabilities->id,
        ]);

        $currentLiabilities = AccountGroup::create([
            'name' => 'Current Liabilities',
            'type' => 'liability',
            'parent_account_group_id' => $liabilities->id,
        ]);

        $sundryCreditors = AccountGroup::create([
            'name' => 'Accounts Payable (Creditors)',
            'type' => 'liability',
            'parent_account_group_id' => $currentLiabilities->id,
        ]);

        $sundryCreditors->accounts()->createMany([
            [
                'name' => 'Bagdad Distributor Agrabad',
                'number' => '201-001',
            ],
            [
                'name' => 'Hasib Traders',
                'number' => '201-002',
            ],
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
            'parent_account_group_id' => $liabilities->id,
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

        // Income Type Groups
        $incomes = AccountGroup::create([
            'name' => 'Incomes',
            'type' => 'income',
            'parent_account_group_id' => null,
        ]);

        $directIncomes = AccountGroup::create([
            'name' => 'Direct Incomes',
            'type' => 'income',
            'parent_account_group_id' => $incomes->id,
        ]);

        $indirectIncomes = AccountGroup::create([
            'name' => 'Indirect Incomes',
            'type' => 'income',
            'parent_account_group_id' => $incomes->id,
        ]);

        $salesAccounts = AccountGroup::create([
            'name' => 'Sales Accounts',
            'type' => 'income',
            'parent_account_group_id' => $incomes->id,
        ]);

        $salesAccounts->accounts()->createMany([
            ['name' => 'Sales - Grocery Items', 'number' => '401-001'],
            ['name' => 'Sales - Packaged Food & Beverages', 'number' => '401-002'],
            ['name' => 'Sales - Health & Beauty Products', 'number' => '401-003'],
            ['name' => 'Sales - Electronic Accessories', 'number' => '401-004'],
            ['name' => 'Sales - Miscellaneous Items', 'number' => '401-005'],
            ['name' => 'Sales Returns & Allowances (Contra)', 'number' => '401-006'],
        ]);

        // Equity Group Type
        $equity = AccountGroup::create([
            'name' => 'Equity',
            'type' => 'equity',
            'parent_account_group_id' => null,
        ]);

        $capitalAccount = AccountGroup::create([
            'name' => 'Capital Account',
            'type' => 'equity',
            'parent_account_group_id' => $equity->id,
        ]);

        $capitalAccount->accounts()->createMany([
            ['name' => "Owner's Capital", 'number' => '301-001'],
            ['name' => "Owner's Drawings (Contra)", 'number' => '301-002'],
        ]);

        $reservesAndSurplus = AccountGroup::create([
            'name' => 'Reserves & Surplus',
            'type' => 'equity',
            'parent_account_group_id' => $equity->id,
        ]);

        $reservesAndSurplus->accounts()->createMany([
            ['name' => 'Retained Earnings', 'number' => '302-001'],
            ['name' => 'General Reserve', 'number' => '302-002'],
        ]);
    }

    public function journalEntrySeeder()
    {
        DB::table('journal_entries')->truncate();
        DB::table('journal_entry_lines')->truncate();

        /// dd(Account::pluck('name'));
        DB::transaction(function () {

            $mainCashAccount = Account::where('name', 'Main Cash')->first();
            $pettyCashAccount = Account::where('name', 'Petty Cash')->first();
            $ownerCapitalAccount = Account::where('name', "Owner's Capital")->first();
            $purchasesGroceryItemsAccount = Account::where('name', "Purchases - Grocery Items")->first();
            $purchasesHealthAndBeautyProductsAccount = Account::where('name', "Purchases - Health & Beauty Products")->first();
            $PurchasesPackagedFoodAndBeveragesAccount = Account::where('name', "Purchases - Packaged Food & Beverages")->first();
         

            $journalEntry = JournalEntry::create([
                'date' => '2024-04-01',
                'description' => 'Testing',
            ]);

            $journalEntry->journalEntryLines()->createMany([
                [
                    'account_id' => $mainCashAccount->id,
                    'debit' => 40000,
                ],
                [
                    'account_id' => $ownerCapitalAccount->id,
                    'credit' => 40000,
                ],
            ]);

            $journalEntry = JournalEntry::create([
                'date' => '2024-06-01',
                'description' => 'Testing',
            ]);

            $journalEntry->journalEntryLines()->createMany([
                [
                    'account_id' => $pettyCashAccount->id,
                    'debit' => 10000,
                ],
                [
                    'account_id' => $ownerCapitalAccount->id,
                    'credit' => 10000,
                ],
            ]);

            $journalEntry = JournalEntry::create([
                'date' => '2024-08-01',
            ]);

            $journalEntry->journalEntryLines()->createMany([
                [
                    'account_id' => $purchasesGroceryItemsAccount->id,
                    'debit' => 100,
                ],
                [
                    'account_id' => $pettyCashAccount->id,
                    'credit' => 100,
                ],
            ]);

            $journalEntry = JournalEntry::create([
                'date' => '2024-08-16',
            ]);

            $journalEntry->journalEntryLines()->createMany([
                [
                    'account_id' => $purchasesHealthAndBeautyProductsAccount->id,
                    'debit' => 20,
                ],
                [
                    'account_id' => $pettyCashAccount->id,
                    'credit' => 20,
                ],
            ]);

            $journalEntry = JournalEntry::create([
                'date' => '2024-08-26',
            ]);

            $journalEntry->journalEntryLines()->createMany([
                [
                    'account_id' => $purchasesHealthAndBeautyProductsAccount->id,
                    'debit' => 220,
                ],
                [
                    'account_id' => $mainCashAccount->id,
                    'credit' => 220,
                ],
            ]);

            $journalEntry = JournalEntry::create([
                'date' => '2024-08-20',
            ]);

            $journalEntry->journalEntryLines()->createMany([
                [
                    'account_id' => $PurchasesPackagedFoodAndBeveragesAccount->id,
                    'debit' => 50,
                ],
                [
                    'account_id' => $mainCashAccount->id,
                    'credit' => 50,
                ],
            ]);

            $journalEntry = JournalEntry::create([
                'date' => '2024-08-24',
            ]);

            $journalEntry->journalEntryLines()->createMany([
                [
                    'account_id' => $PurchasesPackagedFoodAndBeveragesAccount->id,
                    'debit' => 50,
                ],
                [
                    'account_id' => $mainCashAccount->id,
                    'credit' => 50,
                ],
            ]);




        });
    }
}



// php artisan db:seed --class="Database\Seeders\Demo\DemoSeeder"
