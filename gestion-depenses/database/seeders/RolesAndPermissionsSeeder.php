<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Réinitialiser les rôles et permissions en cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Créer les permissions
        // Dépenses
        Permission::create(['name' => 'view expenses']);
        Permission::create(['name' => 'create expenses']);
        Permission::create(['name' => 'edit expenses']);
        Permission::create(['name' => 'delete expenses']);
        
        // Revenus
        Permission::create(['name' => 'view incomes']);
        Permission::create(['name' => 'create incomes']);
        Permission::create(['name' => 'edit incomes']);
        Permission::create(['name' => 'delete incomes']);
        
        // Catégories
        Permission::create(['name' => 'view categories']);
        Permission::create(['name' => 'create categories']);
        Permission::create(['name' => 'edit categories']);
        Permission::create(['name' => 'delete categories']);
        
        // Utilisateurs
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);
        
        // Créer les rôles et assigner les permissions
        // Rôle utilisateur standard
        $role = Role::create(['name' => 'user']);
        $role->givePermissionTo([
            'view expenses', 'create expenses', 'edit expenses', 'delete expenses',
            'view incomes', 'create incomes', 'edit incomes', 'delete incomes',
            'view categories'
        ]);
        
        // Rôle administrateur
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());
        
        // Assigner le rôle admin au premier utilisateur (ID=1)
        $admin = User::find(1);
        if ($admin) {
            $admin->assignRole('admin');
        }
        
        // Assigner le rôle utilisateur aux autres utilisateurs
        $users = User::where('id', '>', 1)->get();
        foreach ($users as $user) {
            $user->assignRole('user');
        }
    }
}
