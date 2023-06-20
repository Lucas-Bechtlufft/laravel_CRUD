<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $user = \App\Models\User::find(1); // ID do usuário que deseja definir como administrador
    
        if ($user) {
            $user->admin = true;
            $user->save();
        }
    }
    
}
