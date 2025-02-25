<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
//addd
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;
// add
use Illuminate\Support\Facades\Auth;


class RoleController extends Controller
{
    // Proteccion de rutas:
    public function __construct(){
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->can('Ver todo')
                && !Auth::user()->can('Administracion') ) {
                    return response()->view('usuarios_vistas.users.error_403', [], 403);
            }
            return $next($request);
        });
    }
    
    public function ver_roles_permisos()
    {
        // Obtener todos los roles
        $roles = Role::all();

        // Obtener todos los permisos disponibles
        $permisos = Permission::all();

        // Obtener los permisos asignados a cada rol y area a cada rol
        $permisosAsignados = [];
        foreach ($roles as $rol) {
            $permisosAsignados[$rol->id] = $rol->permissions;
        }

        return view('usuarios_vistas.roles.lista_roles_permisos', compact('roles', 'permisos', 'permisosAsignados'));
    }

    // Métod para obtener los datos del rol
    public function asignar_permiso($id_rol)
    {

        $rol= Role::find($id_rol);
        Log::info('Rol encontrado: '.json_encode($rol));

        $permisosAsignados = $rol->permissions;
        Log::info('Permisos asignados del rol: '.json_encode($rol).' ['.json_encode($permisosAsignados).']');

        $permisos = Permission::all();

        return response()->json(['rol'=>$rol, 'permisos'=> $permisos, 'permisosAsignados'=>$permisosAsignados]);
    }

    // Metodo para asignar los permisos:
    public function update_permisos(Request $request)
    {
        try{
            $id_rol = $request->input('id_rol');

            $rol = Role::find($id_rol);
            Log::info('Rol obtenido mediante el ID => '. json_encode($rol));

            if($rol){
                //  $permisos = $request->permisos;
                //  Log::info('Permisos obtenidos: '.json_encode($permisos));
                $rol->permissions()->sync($request->permisos);

                // Limpia la caché de permissions del proyecto
                app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


                Log::info("Administración del rol realizada con éxito => ".json_encode($rol));
                return redirect()->route("usuario.roles.lista", $rol);
            }else{
                Log::error('El usuario no fue encontrado en la base de datos');
                return response()->json(['error' => true, 'message' => 'Administración del usuario erronea.']);
            }

        }catch(\Exception $ex) {
            Log::error(['error' => 'Error al realizar la administración del rol: ' . $ex->getMessage()]);
            return response()->json(['error' => true, 'message' => 'Administración del rol no realizada.']);
        }
    }

    // Metodo para agregar roles
    public function   guardar_roles(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'nombre'=>'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            } else{
                $name = $request->input('nombre');

                $rol = new Role([
                    'name'=>$name,
                    'guard_name' => 'web'
                ]);

                $rol->save();

                Log::info('Rol agregado: [ ' . $rol.' ]');
                return response()->json(['message' => 'Datos guardados correctamente'], 202);
            }

        }catch(\Exception $ex){
            Log::error(['error' => 'Error al guardar el rol: ' . $ex->getMessage()]);
            return response()->json(['error en el guardado del rol'], 500);
        };
    }

    // Metodo para agregar permisos
    public function guardar_permisos(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'nombre'=>'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }else{
                $name = $request->input('nombre');

                $permiso = new Permission([
                    'name'=>$name,
                    'guard_name' => 'web'
                ]);

                $permiso->save();

                Log::info('Datos enviados a la tabla permissions: [ ' . $permiso.' ]');
                return response()->json(['message' => 'Datos guardados correctamente'], 202);
            }

        }catch(\Exception $ex){
            Log::error(['error' => 'Error al guardar el permiso: ' . $ex->getMessage()]);
            return response()->json(['error en el guardado del permiso'], 500);
        };
    }

}
