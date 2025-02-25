<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pais;
use App\Models\Corporativo;
use App\Models\Cargo;
use App\Models\Area;


class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pais::create([
            'nombre'=>'Colombia',
        ]);
        Corporativo::create([
            'nombre'=>'Aje Colombia',
            'id_pais'=>1,
        ]);
        //Creando cargos por default
        $cargos = [
            ['nombre' => 'Super Administrador TI'],
            ['nombre' => 'Administrador TI'],
            ['nombre' => 'Jefe'],
            ['nombre' => 'Auxiliar'],
            ['nombre' => 'Gerente'],
            ['nombre' => 'Empleado'],
        ];
        Cargo::insert($cargos);
        //Creando áreas por default
        $areas = [
            ['nombre'=>'TI'],
            ['nombre' => 'Administracion'],
            ['nombre' => 'Operaciones'],
            ['nombre' => 'Contabilidad'],
            ['nombre' => 'Credito y cobranzas'],
        ];
        Area::insert($areas);
    }
}
