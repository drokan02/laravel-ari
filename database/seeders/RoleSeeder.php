<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'administrador'
        ]);
        Role::create([
            'name' => 'ejecutivo'
        ]);
        Role::create([
            'name' => 'contador'
        ]);
    }
}
