<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [

            ['id' => 1, 'name' => 'Dashboard', 'group_prefix' => 'Dashboard'],

            ['id' => 11, 'name' => 'User List', 'group_prefix' => 'Users'],
            ['id' => 12, 'name' => 'User Create', 'group_prefix' => 'Users'],
            ['id' => 13, 'name' => 'User Edit', 'group_prefix' => 'Users'],
            ['id' => 14, 'name' => 'User Delete', 'group_prefix' => 'Users'],

            ['id' => 21, 'name' => 'Role List', 'group_prefix' => 'Role'],
            ['id' => 22, 'name' => 'Role Create', 'group_prefix' => 'Role'],
            ['id' => 23, 'name' => 'Role Edit', 'group_prefix' => 'Role'],
            ['id' => 24, 'name' => 'Role Delete', 'group_prefix' => 'Role'],

            ['id' => 31, 'name' => 'Attribute List', 'group_prefix' => 'Attribute'],
            ['id' => 32, 'name' => 'Attribute Create', 'group_prefix' => 'Attribute'],
            ['id' => 32, 'name' => 'Attribute Edit', 'group_prefix' => 'Attribute'],
            ['id' => 34, 'name' => 'Attribute Delete', 'group_prefix' => 'Attribute'],

            ['id' => 41, 'name' => 'Backup List', 'group_prefix' => 'Backup'],
            ['id' => 42, 'name' => 'Backup Create', 'group_prefix' => 'Backup'],
            ['id' => 43, 'name' => 'Backup Download', 'group_prefix' => 'Backup'],
            ['id' => 44, 'name' => 'Backup Delete', 'group_prefix' => 'Backup'],

            ['id' => 51, 'name' => 'CashDrawer List', 'group_prefix' => 'CashDrawer'],
            ['id' => 52, 'name' => 'CashDrawer Create', 'group_prefix' => 'CashDrawer'],
            ['id' => 53, 'name' => 'CashDrawer Edit', 'group_prefix' => 'CashDrawer'],
            ['id' => 54, 'name' => 'CashDrawer Report', 'group_prefix' => 'CashDrawer'],

            ['id' => 61, 'name' => 'Category List', 'group_prefix' => 'Category'],
            ['id' => 62, 'name' => 'Category Create', 'group_prefix' => 'Category'],
            ['id' => 63, 'name' => 'Category Edit', 'group_prefix' => 'Category'],
            ['id' => 64, 'name' => 'Category Delete', 'group_prefix' => 'Category'],

            ['id' => 71, 'name' => 'Client List', 'group_prefix' => 'Client'],
            ['id' => 72, 'name' => 'Client Create', 'group_prefix' => 'Client'],
            ['id' => 73, 'name' => 'Client Edit', 'group_prefix' => 'Client'],
            ['id' => 74, 'name' => 'Client Delete', 'group_prefix' => 'Client'],

            ['id' => 81, 'name' => 'Discount Page', 'group_prefix' => 'Discount'],
            ['id' => 82, 'name' => 'Discount Create', 'group_prefix' => 'Discount'],
            ['id' => 83, 'name' => 'Discount Edit', 'group_prefix' => 'Discount'],
            ['id' => 84, 'name' => 'Discount Add Item', 'group_prefix' => 'Discount'],
            ['id' => 85, 'name' => 'Discount remove Item', 'group_prefix' => 'Discount'],
            ['id' => 86, 'name' => 'Discount Change Price', 'group_prefix' => 'Discount'],

            ['id' => 91, 'name' => 'ExpenseCategory List', 'group_prefix' => 'ExpenseCategory'],
            ['id' => 92, 'name' => 'ExpenseCategory Create', 'group_prefix' => 'ExpenseCategory'],
            ['id' => 93, 'name' => 'ExpenseCategory Edit', 'group_prefix' => 'ExpenseCategory'],
            ['id' => 94, 'name' => 'ExpenseCategory Delete', 'group_prefix' => 'ExpenseCategory'],

            ['id' => 101, 'name' => 'Expense List', 'group_prefix' => 'Expense'],
            ['id' => 102, 'name' => 'Expense Create', 'group_prefix' => 'Expense'],
            ['id' => 103, 'name' => 'Expense Edit', 'group_prefix' => 'Expense'],
            ['id' => 104, 'name' => 'Expense Delete', 'group_prefix' => 'Expense'],
            ['id' => 104, 'name' => 'Expense Report', 'group_prefix' => 'Expense'],

            ['id' => 111, 'name' => 'Item List', 'group_prefix' => 'Item'],
            ['id' => 112, 'name' => 'Item Create', 'group_prefix' => 'Item'],
            ['id' => 113, 'name' => 'Item Edit', 'group_prefix' => 'Item'],
            ['id' => 114, 'name' => 'Item Delete', 'group_prefix' => 'Item'],
            ['id' => 115, 'name' => 'Item Show', 'group_prefix' => 'Item'],
            ['id' => 116, 'name' => 'Item Import', 'group_prefix' => 'Item'],
                 
            ['id' => 121, 'name' => 'Purchase List', 'group_prefix' => 'Purchase'],
            ['id' => 122, 'name' => 'Purchase Create', 'group_prefix' => 'Purchase'],
            ['id' => 123, 'name' => 'Purchase Edit', 'group_prefix' => 'Purchase'],
            ['id' => 124, 'name' => 'Purchase Delete', 'group_prefix' => 'Purchase'],
            ['id' => 125, 'name' => 'Purchase BillPayment', 'group_prefix' => 'Purchase'],
            ['id' => 126, 'name' => 'Purchase Move To Stock', 'group_prefix' => 'Purchase'],

            ['id' => 131, 'name' => 'Report Stock', 'group_prefix' => 'Report'],
            ['id' => 132, 'name' => 'Report Sale', 'group_prefix' => 'Report'],
            ['id' => 133, 'name' => 'Report Sale Details', 'group_prefix' => 'Report'],
            ['id' => 134, 'name' => 'Report Purchase', 'group_prefix' => 'Report'],
            ['id' => 135, 'name' => 'Report Purchase Details', 'group_prefix' => 'Report'],
            ['id' => 136, 'name' => 'Profit and loss Report', 'group_prefix' => 'Report'],

            ['id' => 141, 'name' => 'Sale List', 'group_prefix' => 'Sale'],
            ['id' => 142, 'name' => 'Sale Create', 'group_prefix' => 'Sale'],
            ['id' => 143, 'name' => 'Sale Edit', 'group_prefix' => 'Sale'],
            ['id' => 144, 'name' => 'Sale Delete', 'group_prefix' => 'Sale'],

            ['id' => 151, 'name' => 'Setting', 'group_prefix' => 'Setting'],
                 
            ['id' => 161, 'name' => 'Supplier List', 'group_prefix' => 'Supplier'],
            ['id' => 162, 'name' => 'Supplier Create', 'group_prefix' => 'Supplier'],
            ['id' => 163, 'name' => 'Supplier Edit', 'group_prefix' => 'Supplier'],
            ['id' => 164, 'name' => 'Supplier Delete', 'group_prefix' => 'Supplier'],
                 
        ];

        foreach ($permissions as  $value) {
            Permission::updateOrCreate(['id' => $value['id']],[
                'name' => $value['name'],
                'group_prefix' => $value['group_prefix'],
            ]);
        }

        $role = Role::find(1);
        $permissions = Permission::pluck('id','id');
        $role->syncPermissions($permissions);
    }
}
