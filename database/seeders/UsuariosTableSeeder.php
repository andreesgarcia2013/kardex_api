<?php

namespace Database\Seeders;

use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsuariosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRoleId = Rol::where('rol', 'Administrador')->value('id_rol');

        // Crear un usuario con el rol "admin"
        Usuario::create([
            'nombre' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('root'),
            'id_rol' => $adminRoleId, // Asignar el ID del rol "admin"
        ]);
    }
}
