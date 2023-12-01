<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'view gs',
            'edit gs',
            'create gs',

            'view book',
            'create book',
            'edit book',
            'delete book',

            'view category',
            'create category',
            'edit category',
            'delete category',

            'view publisher',
            'create publisher',
            'edit publisher',
            'delete publisher',

            'view author',
            'create author',
            'edit author',
            'delete author',

            'view location',
            'create location',
            'edit location',
            'delete location',

            'view language',
            'create language',
            'edit language',
            'delete language',

            'view bookrequest',
            'create bookrequest',
            'edit bookrequest',
            'delete bookrequest',

            'view transaction',
            'create transaction',
            'edit transaction',
            'delete transaction',

            'view permission',
            'edit permission'
        ];

        foreach ($permissions as $value) {
            Permission::create([
                'name' => $value,
            ]);
        }

        $role = Role::findByName('admin');
        $role->givePermissionTo($permissions);
    }
}
