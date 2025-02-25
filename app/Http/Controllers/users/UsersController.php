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

//
use App\Models\Area;
use App\Models\Cargo;
use App\Models\Corporativo;
// add
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// SPREEDSHEET -> Para subir el archivo excel con la lista de usuarios
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\IOFactory;


class UsersController extends Controller
{
    // Proteccion de rutas:
    public function __construct(){
        $this->middleware(function ($request, $next) {
            if ($request->route()->getName() === 'admin.usuario.profile') {
                return $next($request);
            }
            if (!Auth::user()->can('Ver todo')
                && !Auth::user()->can('Administracion')) {
                    return response()->view('usuarios_vistas.users.error_403', [], 403);
            }
            return $next($request);
        });
    }


    public function index(){
        return view('usuarios_vistas.users.lista_users');
    }

    // List of users for datatables
    public function lista_usuarios(Request $request) {
        //Log::info('Request: '.json_encode($request->all()));
        $query = User::with('roles', 'cargos', 'areas', 'corporativos');

        if($request->nombre != ''){
            $query->where('users.name', 'like', '%' . $request->nombre . '%');
        }
        if($request->email != ''){
            $query->where('users.email', 'like', '%' .$request->email . '%');
        }

        $usersLista = $query;
        // Log::info('USERSlISTA: '.json_encode($query->get()));

        return DataTables::of($usersLista)
            ->addColumn('action', function ($data){
                $dropdown = '<div class="dropdown">';
                $dropdown .= '<button class="btn btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown" id="dropdownMenuButton" aria-expanded="false">';
                $dropdown .= 'Acciones';
                $dropdown .= '</button>';
                $dropdown .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="padding: 3px;">';

                $dropdown .= '<button onclick="asignarAdministracion('.$data->id.')" class="dropdown-item btn btn-outline-info" title="Administrar usuario" style="color:#0d6efd; background-color: #B4E4FF;"><i class="fas fa-user-cog" style="font-size: 18px;"></i> Administrar</button>';
                $dropdown .= '<button onclick="modificarUsuario('.$data->id. ')" class="dropdown-item btn btn-outline-info mt-1" title="Modificar usuario" style="color: orange; background-color: rgba(255,255,0,0.32);"><i class="bx bx-edit-alt" style="font-size: 18px;"></i> Modificar</button>';
                $dropdown .= '<button onclick="eliminarUsuario(event, '.$data->id.')" class="dropdown-item btn btn-outline-danger mt-1" title="Eliminar usuario" style="background-color: #FFE8E9; color: #BA000B; "><i class="fas fa-trash" style="font-size: 18px;"></i> Eliminar</button>';

                $dropdown .= '</div>';
                $dropdown .= '</div>';

                return $dropdown;
            })
            ->toJson();
    }


    // Método para agregar usuario
    public function guardar_usuario(Request $request)
    {
        try{

            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|min:5',
                'email' => 'required|string|email|unique:users,email',
                'password' => 'required|string|min:8',
                'confirm_password' => 'required|string|same:password',
            ]);

            if($validator->fails()){
                return response()->json(['error' => true, 'errors' => $validator->errors()]);
            }

            $nombre = $request->input('nombre');
            $email = $request->input('email');
            $password = $request->input('password');
            $confirm_password = $request->input('confirm_password');

            if($password === $confirm_password){
                $passwordHash = bcrypt($password);
            }

            $usuario = new User([
                'name'=>$nombre,
                'email'=>$email,
                'password'=>$passwordHash
            ]);

            $usuario->save();

            Log::info('Usuario agregado correctamente: '.json_encode($usuario));
            return response()->json(['success' => true, 'message' => 'Usuario agregado correctamente.']);

        }catch(\Exception $ex){
            Log::error(['error' => 'Error al agregar el usuario: ' . $ex->getMessage()]);
            return response()->json(['error' => true, 'message' => 'Usuario no agregado.']);
        }
    }

    // Método para obtener los datos del usuario por ID:
    public function administracion_usuario(int $id)
    {
        try{

            if(request()->ajax()){
                $user = User::with('roles', 'cargos', 'areas', 'corporativos')->find($id);
                Log::info('Datos obtenidos para la administracion del usuario: [ '.$user.' ]');

                $roles = Role::all();
                $cargos = Cargo::all();
                $areas = Area::all();
                $corporativos = Corporativo::all();

                return response()->json([
                    'user' => $user,
                    'roles' => $roles,
                    'assignedRoles' => $user->roles,
                    'cargos' => $cargos,
                    'assignedCargos' => $user->cargos,
                    'areas' => $areas,
                    'assignedAreas' => $user->areas,
                    'corporativos' => $corporativos,
                    'assignedCorporativos' => $user->corporativos,
                ]);
            }

        }catch(\Exception $ex){
            Log::error(['error' => 'Error al acceder a los datos del usuario (administracion: ' . $ex->getMessage()]);
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }

    // Método para guardar la administracion del usuario (actualizar)
    public function guardar_administracion_usuario(Request $request) {
        try {
            $id_user = $request->input('id_user_config');
            $user = User::find($id_user);
            // Log::info('Usuario obtenido mediante el ID => ' . json_encode($user));

            if ($user) {
                $currentUser = auth()->user();
                // Log::info('Usuario logueado: ' . json_encode($currentUser));
                $currentUserLogRoles = $currentUser->roles->pluck('name')->toArray();
                // Log::info('Roles del usuario logueado: ' . json_encode($currentUserLogRoles));

                $roles = $request->input('roles', []);
                if (!is_array($roles)) {
                    $roles = explode(',', $roles);
                }
                // Log::info('Roles: ' . json_encode($roles));

                // Obteniendo los nombres de los roles a asignar
                $rolesToAssign = Role::whereIn('id', $roles)->pluck('name');
                // Log::info('Roles para asignar: ' . json_encode($rolesToAssign));

                // Verificación de permisos y roles
                if (in_array('Super Admin', $currentUserLogRoles)) {
                    // El Super Admin puede asignar cualquier rol
                    $user->roles()->sync($roles);
                } else if (in_array('Admin', $currentUserLogRoles)) {
                    // El Admin no puede asignar el rol Super Admin
                    if ($rolesToAssign->contains('Super Admin')) {
                        Log::info('Intento de asignar rol Super Admin por un usuario con rol Admin');
                        return response()->json(['error' => true, 'message' => 'Usted no puede asignar el rol de Super Admin']);
                    } else {
                        $user->roles()->sync($roles);
                    }
                } else {
                    Log::info('El usuario logueado no tiene permisos para asignar roles');
                    return response()->json(['error' => true, 'message' => 'Usted no tiene permisos para asignar roles']);
                }

                // Asignacion de cargos - areas - corporativos
                $user->cargos()->sync($request->input('cargos', []));
                $user->areas()->sync($request->input('areas', []));
                $user->corporativos()->sync($request->input('corporativos', []));

                Log::info("Administración del usuario realizada con éxito => " . json_encode($user));
                return response()->json(['success' => true, 'message' => 'Administracion realizada correctamente.']);
            } else {
                Log::error('El usuario no fue encontrado en la base de datos');
                return response()->json(['error' => true, 'message' => 'Administración del usuario errónea.']);
            }

        } catch (\Exception $ex) {
            Log::error(['error' => 'Error al realizar la administración del usuario: ' . $ex->getMessage()]);
            return response()->json(['error' => true, 'message' => 'Administración del usuario no realizada.']);
        }
    }

    /*******************************************************************************************************/
    // Metodos para editar y actualizar los registros
    public function edit_usuario(int $id)
    {
        // Log::info('llegó al controller: ');
        try{
            if(request()->ajax()){
                $user = User::find($id);
                Log::info('Datos obtenidos del user: '.$user);
                return response()->json(['user'=>$user]);
            }
        }catch(\Exception $ex){
            Log::error(['error' => 'Error al acceder a los datos del user (administracion: ' . $ex->getMessage()]);
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }

    public function update_usuario(Request $request)
    {
        Log::info('Datos recibidos for update users: ' . json_encode($request->all()));

        try {
            $id_user = $request->input('id_usuario');
            // Log::info('ID_USER: ' . json_encode($id_user));

            if ($id_user === null) {
                return response()->json(['error' => true, 'message' => 'ID del user no recibido'], 400);
            }

            $user_update = User::findOrFail($id_user);
            // Log::info('user obtenido mediante el ID => ' . json_encode($user_update));

            if (!$user_update) {
                return response()->json(['error' => true, 'message' => 'El user no se encontró en la base de datos'], 404);
            }

            $validator = Validator::make($request->all(), [
                'nombre_input' => 'required',
                'email_input'  => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => true, 'errors' => $validator->errors()]);
            }

            $user_update->name  = $request->input('nombre_input');
            $user_update->email = $request->input('email_input');
            $user_update->save();

            Log::info('user actualizado: ' . json_encode($user_update));

            return response()->json(['success' => true, 'message' => 'Usuario actualizado correctamente']);
        } catch (\Exception $ex) {
            Log::error('Error al actualizar el user: ' . $ex->getMessage());
            return response()->json(['error' => true, 'message' => 'Ocurrió un error al actualizar el user'], 500);
        }
    }

    public function delete_usuario(int $id)
    {
        try{
            if(request()->ajax()){
                $user = User::findOrFail($id);
                // Log::info('Datos obtenidos del pais: '.$user);
                $user->delete();

                Log::info('user eliminado: '.$user);
                return response()->json(['message' => 'El user ha sido eliminado correctamente']);
            }
        }catch(\Exception $ex){
            Log::error(['error' => 'Error al acceder a los datos del user (administracion: ' . $ex->getMessage()]);
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }
    /*******************************************************************************************************/
    
    // Edición del perfil del usuario:
    public function edit_profile_users()
    {
        return view('usuarios_vistas.users.edit_profile_users');
    }

    /*******************************************************************************************************/
    public function subir_usuarios_excel(Request $request){
        DB::beginTransaction();
        try{
            $request->validate([
                'file' => 'required|mimes:xlsx,xls|max:2048', // Validación del archivo
            ]);

            $file = $request->file('file');
            Log::info('Archivo excel: ' .json_encode($file));

            // Cargar el archivo Excel
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();

            // Obtener las filas con datos
            $highestRow = $worksheet->getHighestRow();

            for ($row = 2; $row <= $highestRow; $row++) { // Comenzar desde la fila 2, asumiendo que la fila 1 contiene encabezados
                $name = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                $email = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                $password = $worksheet->getCellByColumnAndRow(3, $row)->getValue();

                if (empty(trim($name))) {
                    Log::alert('Usuario sin nombre en la fila ' . $row);
                    continue;
                }
                if (empty(trim($email))) {
                    Log::alert('Email vacío en la fila ' . $row);
                    continue;
                }
                if (empty(trim($password))) {
                    Log::alert('Contraseña vacía en la fila ' . $row);
                    continue;
                }
                $email_search = DB::table('users')->where('email', $email)->exists();
                if ($email_search) {
                    Log::alert('Email existente: ' . $email);
                    continue;
                }

                // Encripta la contraseña
                $passwordHash = bcrypt($password);

                // Crea el usuario
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => $passwordHash,
                ]);

                Log::info('user agregado: '.json_encode($user));
            }
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Datos guardados']);
        }catch(\Exception $e){
            DB::rollBack();
            Log::error(['error' => 'Error al procesar el excel: ' . $e->getMessage()]);
            return response()->json(['error' => true, 'message' => 'Error al procesar el excel']);
        }
    }
    /*******************************************************************************************************/


}
