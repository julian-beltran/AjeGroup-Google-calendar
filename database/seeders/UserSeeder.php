<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Creando usuario para agregar el rol
        $user = User::create([
            'name'=>'Jean Alexander Romero',
            'email' => 'jeanalexromero200@gmail.com',
            'password' => bcrypt('12345678')
        ]);
        $user->assignRole('Super Admin');
        $user->areas()->attach(1);

        User::create([
            'name'=>'Camila Roman',
            'email' => 'camila@gmail.com',
            'password' => bcrypt('12345678')
        ]);
        User::create([
            'name'=>'Carla Ramirez',
            'email' => 'carla@gmail.com',
            'password' => bcrypt('12345678')
        ]);

        User::create([
            'name'=>'Alexander Romero',
            'email' => 'alexander.romero@efletex.com',
            'password' => bcrypt('12345678')
        ])->assignRole('Admin');
        User::create([
            'name'=>'Luis',
            'email' => 'luis@gmail.com',
            'password' => bcrypt('12345678')
        ])->assignRole('Colaborador');

        /*
        User::create([
            'name'=>'Andres Medina',
            'email' => 'andres@gmail.com',
            'password' => bcrypt('12345678')
        ]);
        User::create([
            'name'=>'Gerente General',
            'email' => 'gerente@gmail.com',
            'password' => bcrypt('12345678')
        ])->assignRole('Leader');

        User::create([
            'name'=>'Alberto',
            'email' => 'alberto@gmail.com',
            'password' => bcrypt('12345678')
        ])->assignRole('Colaborador');
        User::create([
            'name'=>'Juan',
            'email' => 'juan@gmail.com',
            'password' => bcrypt('12345678')
        ]);
        User::create([
            'name'=>'Laura',
            'email' => 'laura@gmail.com',
            'password' => bcrypt('12345678')
        ]);
        User::create([
            'name'=>'Karmen Restrejo',
            'email' => 'karmen@gmail.com',
            'password' => bcrypt('12345678')
        ]);
        User::create([
            'name'=>'Javier',
            'email' => 'javier@gmail.com',
            'password' => bcrypt('12345678')
        ]);
        User::create([
            'name'=>'Deysi Mch',
            'email' => 'deysi@gmail.com',
            'password' => bcrypt('12345678')
        ]);
        User::create([
            'name'=>'Alexander Romero',
            'email' => 'alex@gmail.com',
            'password' => bcrypt('12345678')
        ]);*/
    }
}
