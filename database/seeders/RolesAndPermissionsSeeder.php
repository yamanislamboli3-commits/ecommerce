<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
               
      
        $permissions = [
            // Products
            'view products',
            'create products',
            'update products',
            'delete products',

            // Customers
            'view users',
            'create users',
            'update users',
            'delete users',

            // Cart
            'view cart',
            'add to cart',
            'update cart',
            'remove from cart',

            // Orders
            'view orders',
            'checkout order',
            'cancel order',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

       
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $customer = Role::firstOrCreate(['name' => 'customer']);

       
        $admin->givePermissionTo([
            'view products',
            'create products',
            'update products',
            'delete products',

            'view users',
            'create users',
            'update users',
            'delete users',

            'view cart',
            'add to cart',
            'update cart',
            'remove from cart',

            'view orders',
            'checkout order',
            'cancel order',
        ]);

      
        $customer->givePermissionTo([
            'view products',

            'view cart',
            'add to cart',
            'update cart',
            'remove from cart',

            'view orders',
            'checkout order',
            'cancel order',
        ]);
        $adminuser=User::firstOrCreate([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            "password"=>Hash::make("password123")
        ]);
        $adminuser->assignRole($admin);
    }
}
