<?php

namespace App\Http\Controllers\espacios;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Cargo;
use App\Models\Corporativo;
use App\Models\Espacio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
// add
use Illuminate\Support\Facades\Auth;

class EspacioController extends Controller
{
    // Proteccion de rutas
    public function __construct(){
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->can('Ver todo')
                && !Auth::user()->can('Administracion')) {
                    return response()->view('usuarios_vistas.users.error_403', [], 403);
            }
            return $next($request);
        });
    }
    
    // Método index -> Muestra todos los espacios creados:
    public function lista_espacios()
    {
        $corporativos = Corporativo::with('pais')->get();
        $espacios = Espacio::with('cargos', 'areas')->get();
        $cargos = Cargo::all();
        $areas = Area::all();

        return view('usuarios_vistas.espacios.lista_espacios', compact('corporativos', 'espacios', 'cargos', 'areas'));
    }

    // prueba ajax espacios request: 
    public function espacios_data(){
        $espacios = Espacio::with('corporativo', 'cargos', 'areas')->get();

    
        return response()->json(['status'=>'ok', 'vista'=>view('usuarios_vistas.espacios.cardEspacios', compact('espacios'))->render()]);
    }
    
    // Metodo para agregar espacios:
    public function agregar_espacio(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'id_corporativo'    => 'required|exists:corporativos,id',
                'nombre'            => 'required|string|min:3',
                'descripcion'       => 'required|string|min:6',
                'frecuencia'        => 'required|string|',
                'imagen_espacio'    => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'guia'              => 'required|string|min:5',
                'tipo_reunion'      => 'required|string'
            ]);

            if($validator->fails()){
                return response()->json(['success' => false, 'errors' => $validator->errors()]);
            }

            // valores de los inputs
            $corporativoId  = $request->input('id_corporativo');
            $nombre         = $request->input('nombre');
            $descripcion    = $request->input('descripcion');
            $config         = json_encode($request->input('config'));
            $frecuencia     = $request->input('frecuencia');
            $guia           = $request->input('guia');
            $tipo_reunion   = $request->input('tipo_reunion');

            // Validacion de la existencia del corporativo
            $corpExistente = Corporativo::find($corporativoId);
            if (!$corpExistente) {
                return response()->json(['error' => 'El corporativo con el ID proporcionado no existe.'], 404);
            }

            // Almacenamiento de la imagen
            if ($imagen = $request->file('imagen_espacio')) {
                $rutaGuardarImg     = 'imageEspacios/';
                $imagenEspacio      = $nombre . "." . $imagen->getClientOriginalExtension();
                $imagen->move($rutaGuardarImg, $imagenEspacio);
            }

            // Creando un objeto de agenda para agregar
            $espacio = new Espacio([
                'nombre'        => $nombre,
                'descripcion'   => $descripcion,
                'frecuencia'    => $frecuencia,
                'config'        => $config,
                'adjunto'       => $imagenEspacio,
                'guia'          => $guia,
                'tipo_reunion'  => $tipo_reunion
            ]);

            // Asociación del corporativo al espacio:
            $espacio->corporativo()->associate($corpExistente);
            $espacio->save();

            Log::info('Espacio agregado: '.$espacio);

            // Asocicacion de cargos y areas:
            $espacio->cargos()->sync($request->cargos);
            $espacio->areas()->sync($request->areas);

            Log::info('Espacio agregado correctamente [' . $espacio->id . '] ' . $espacio->nombre . ' - Corporativo: ' . $espacio->corporativo->nombre);
            return response()->json(['success' => true, 'message' => 'Espacio agregado correctamente.']);

        }catch (\Exception $ex) {
            Log::error('Error al registrar el espacio: ' . $ex->getMessage());
            return response()->json(['error' => 'Error al agregar el espacio'], 500);
        }
    }

    // Método para obtener los cargos y areas del espacio y mostrar en el modal:
    public function ver_datos_del_espacio_asignar($id)
    {
        try{
            $espacio = Espacio::findOrFail($id);

            $cargos = Cargo::all();
            $areas = Area::all();

            Log::info('Data obtenida del espacio: '.json_encode($espacio));
            return response()->json([
                'espacio'=>$espacio,
                'cargos' => $cargos,
                'assignedCargos' => $espacio->cargos,
                'areas' => $areas,
                'assignedAreas' => $espacio->areas,
            ]);
        }catch(\Exception $ex){
            Log::error('Error al acceder al espacio: ' . $ex->getMessage());
            return response()->json(['error' => 'Error al acceder a los datos del espacio'], 500);
        }
    }

    // Método para assignar los cargos y areas al espacio:
    public function update_cargos_areas_de_espacio(Request $request)
    {
        try{
            $id_espacio = $request->input('id_espacio_cargo_area_update');
            Log::info('Id espacio recogida: '.json_encode($id_espacio));

            if ($id_espacio === null) {
                return response()->json(['error' => true, 'message' => 'ID del espacio no recibido'], 400);
            }

            $espacio_asignar = Espacio::findOrFail($id_espacio);
            Log::info('Espacio encontrado: ' . json_encode($espacio_asignar));

            if (!$espacio_asignar) {
                return response()->json(['error' => true, 'message' => 'El espacio no se encontró en la base de datos'], 404);
            }

            // Asociacion de cargos y areas al espacio
            $espacio_asignar->cargos()->sync($request->cargos);
            $espacio_asignar->areas()->sync($request->areas);

            Log::info('Configuración del espacio guardada exitosamente');
            return redirect()->route("espacio.lista", $espacio_asignar);

        }catch(\Exception $ex){
            Log::error('Error al actualizar el espacio: ' . $ex->getMessage());
            return response()->json(['error' => true, 'message' => 'Ocurrió un error al asignar cargos y areas al espacio'], 500);
        }
    }

    // Método para ver los datos del espacio y mostrar en un modal para actualizar:
    public function show_espacio($id)
    {
        try{
            $espacio = Espacio::findOrFail($id);

            Log::info('Data obtenida del espacio: '.json_encode($espacio));
            return response()->json(['espacio'=>$espacio]);
        }catch(\Exception $ex){
            Log::error('Error al acceder al espacio: ' . $ex->getMessage());
            return response()->json(['error' => 'Error al acceder a los datos del espacio'], 500);
        }
    }

    // Método para actualizar los datos del espacio:
    public function update_datos_de_espacio(Request $request)
    {
        try {
            $id_espacio = $request->input('id_espacio_update');
            if ($id_espacio === null) {
                return response()->json(['error' => true, 'message' => 'ID del espacio no recibido'], 400);
            }

            $espacio_update = Espacio::findOrFail($id_espacio);
            Log::info('Espacio encontrado: ' . json_encode($espacio_update));

            if (!$espacio_update) {
                return response()->json(['error' => true, 'message' => 'El espacio no se encontró en la base de datos'], 404);
            }

            $validator = Validator::make($request->all(), [
                'nombre_espacio' => 'required|min:8',
                'descripcion_espacio' => 'required|min:10',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => true, 'errors' => $validator->errors()]);
            }

            // Obtener datos del formulario
            $nombre = $request->input('nombre_espacio');
            $descripcion = $request->input('descripcion_espacio');
            $config = json_encode($request->input('config'));
            $frecuencia = $request->input('frecuencia_data');

            // Actualizar el espacio existente
            $espacio_update->nombre = $nombre;
            $espacio_update->descripcion = $descripcion;
            $espacio_update->config = $config;
            $espacio_update->frecuencia = $frecuencia;
            $espacio_update->save();

            // Respuesta exitosa
            return response()->json(['success' => true, 'message' => 'Espacio actualizado correctamente']);

        } catch (\Exception $ex) {
            Log::error('Error al actualizar el espacio: ' . $ex->getMessage());
            return response()->json(['error' => true, 'message' => 'Ocurrió un error al actualizar el espacio'], 500);
        }
    }

}
