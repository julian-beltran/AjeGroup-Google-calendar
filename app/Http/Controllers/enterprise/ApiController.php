<?php

namespace App\Http\Controllers\enterprise;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\Corporativo;
use App\Models\Cargo;
use App\Models\Pais;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
// For datatables:
use Yajra\DataTables\Facades\DataTables;
// add
use Illuminate\Support\Facades\Auth;


class ApiController extends Controller
{
    // Proteccion de rutas
    public function __construct(){
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->can('Ver todo')
                && !Auth::user()->can('Administracion') ) {
                    return response()->view('usuarios_vistas.users.error_403', [], 403);
            }
            return $next($request);
        });
    }

    public function index()
    {
        $paises = Pais::all();
        return view('usuarios_vistas.enterprise.vista', compact('paises'));
    }

    /******************************************************************************************************************/
    // Vista que trabaja con solicitudes AJAX: Administracion de paises
    /******************************************************************************************************************/
    public function getPaises(Request $request)
    {
        if($request->ajax()){
            $paisLista = Pais::all();
        }
        Log::info('Data de pais: '.json_encode($paisLista));
        return DataTables::of($paisLista)
            ->addIndexColumn()
            ->make(true);
    }

    public function add_pais(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'pais' => 'required|string|unique:pais,nombre',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => true, 'errors' => $validator->errors()]);
            }

            $pais_name = $request->input('pais');
            //crea un nuevo pais
            $pais = new Pais([
                'nombre'=>$pais_name,
            ]);
            $pais->save();

            Log::info('Pais agregado correctamente: ['.$pais->id.'] '.$pais->nombre.' ');

            return response()->json(['success' => true, 'message' => 'Pais agregado correctamente.']);
        }catch (\Exception $ex){
            Log::error('Error al agregar pais: '.$ex->getMessage());

            return response()->json(['error' => true, 'message' => 'Pais no agregado.']);
        }
    }

    public function edit_pais(int $id)
    {
        Log::info('llegó al controller: ');
        try{
            if(request()->ajax()){
                $pais = Pais::find($id);
                Log::info('Datos obtenidos del pais: '.$pais);
                return response()->json(['pais'=>$pais]);
            }
        }catch(\Exception $ex){
            Log::error(['error' => 'Error al acceder a los datos del pais (administracion: ' . $ex->getMessage()]);
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }

    public function update_pais(Request $request)
    {
        try{
            $id_pais = $request->input('id_pais');
            if ($id_pais === null) {
                return response()->json(['error' => true, 'message' => 'ID del pais no recibido'], 400);
            }
            $pais_update = Pais::findOrFail($id_pais);
            Log::info('Pais obtenido mediante el ID => '. json_encode($pais_update));

            if (!$pais_update) {
                return response()->json(['error' => true, 'message' => 'El pais no se encontró en la base de datos'], 404);
            }

            $validator = Validator::make($request->all(), [
                'nombre_input' => 'required|unique:pais,nombre',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => true, 'errors' => $validator->errors()]);
            }

            $nombre = $request->input('nombre_input');

            // actualizar datos:
            $pais_update->nombre = $nombre;
            $pais_update->save();

            Log::info('Pais actualizado: '.$pais_update);

            return response()->json(['success' => true, 'message' => 'Area actualizado correctamente']);
        } catch (\Exception $ex) {
            Log::error('Error al actualizar el pais: ' . $ex->getMessage());
            return response()->json(['error' => true, 'message' => 'Ocurrió un error al actualizar el pais'], 500);
        }
    }

    public function delete_pais(int $id)
    {
        try{
            if(request()->ajax()){
                $pais = Pais::findOrFail($id);
                Log::info('Datos obtenidos del pais: '.$pais);
                $pais->delete();

                Log::info('Pais eliminado: '.$pais);
                return response()->json(['message' => 'El pais ha sido eliminado correctamente']);
            }
        }catch(\Exception $ex){
            Log::error(['error' => 'Error al acceder a los datos del pais (administracion: ' . $ex->getMessage()]);
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }

    /******************************************************************************************************************/
    // Vista que trabaja con solicitudes AJAX: Administracion de corporativos
    /******************************************************************************************************************/
    public function getCorporativos(Request $request)
    {
        if($request->ajax()){
            $corporativoLista = Corporativo::with('pais')->get();
        }
        Log::info('Data de corporativo: '.json_encode($corporativoLista));
        return DataTables::of($corporativoLista)
            ->addColumn('nombre_pais', function ($corporativo) {
                return $corporativo->pais->nombre;
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function add_corporativo(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'corporativo' => 'required|string|unique:corporativos,nombre',
                'pais' => 'required|exists:pais,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => true, 'errors' => $validator->errors()]);
            }
            $nombre = $request->input('corporativo');
            $idPais = $request->input('pais');
            // Verificar si el país existe
            $paisExistente = Pais::find($idPais);
            if (!$paisExistente) {
                return response()->json(['error' => 'El país con el ID proporcionado no existe.'], 404);
            }
            // Crear el corporativo
            $corporativo = new Corporativo([
                'nombre' => $nombre,
                'id_pais' => $idPais,
            ]);
            $corporativo->save();
            // Log
            Log::info('Corporativo agregado correctamente: ['.$corporativo->id.'] '.$corporativo->nombre.' - País: '.$paisExistente->nombre);

            return response()->json(['success' => true, 'message' => 'Corporativo agregado correctamente.']);

        } catch (\Exception $ex) {
            Log::error('Error al registrar el corporativo: '.$ex->getMessage());
            return response()->json(['error' => true, 'message' => 'Corporativo no agregado.']);
        }
    }

    public function edit_corporativo(int $id)
    {
        try{
            if(request()->ajax()){
                $corporativo = Corporativo::with('pais')->find($id);
                $paises = Pais::all();
                Log::info('Datos obtenidos del corporativo: '.$corporativo);
                $assignedPais = $corporativo->pais;

                // Puedes pasar el ID del país asignado o null
                $assignedPaisId = $assignedPais ? $assignedPais->id : null;

                return response()->json([
                    'corporativo'=>$corporativo,
                    'paises'=>$paises,
                    'assignedPaisId'=>$assignedPaisId
                ]);
            }
        }catch(\Exception $ex){
            Log::error(['error' => 'Error al acceder a los datos del corporativo (administracion: ' . $ex->getMessage()]);
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }

    public function update_corporativo(Request $request)
    {
        try{
            $id_corporativo = $request->input('id_corporativo');
            if ($id_corporativo === null) {
                return response()->json(['error' => true, 'message' => 'ID del corporativo no recibido'], 400);
            }
            $corporativo_update = Corporativo::findOrFail($id_corporativo);
            Log::info('corporativo obtenido mediante el ID => '. json_encode($corporativo_update));

            if (!$corporativo_update) {
                return response()->json(['error' => true, 'message' => 'El corporativo no se encontró en la base de datos'], 404);
            }

            $validator = Validator::make($request->all(), [
                'nombre_input_corporativo' => 'required|unique:corporativos,nombre',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => true, 'errors' => $validator->errors()]);
            }

            $nombre = $request->input('nombre_input_corporativo');
            $id_pais = $request->input('pais');

            // actualizar datos:
            $corporativo_update->nombre = $nombre;
            $corporativo_update->id_pais = $id_pais;
            $corporativo_update->save();

            Log::info('corporativo actualizado: '.$corporativo_update);

            return response()->json(['success' => true, 'message' => 'Area actualizado correctamente']);
        } catch (\Exception $ex) {
            Log::error('Error al actualizar el corporativo: ' . $ex->getMessage());
            return response()->json(['error' => true, 'message' => 'Ocurrió un error al actualizar el corporativo'], 500);
        }
    }

    public function delete_corporativo(int $id)
    {
        try{
            if(request()->ajax()){
                $corporativo = Corporativo::findOrFail($id);
                Log::info('Datos obtenidos del corporativo: '.$corporativo);
                $corporativo->delete();

                Log::info('corporativo eliminado: '.$corporativo);
                return response()->json(['message' => 'El corporativo ha sido eliminado correctamente']);
            }
        }catch(\Exception $ex){
            Log::error(['error' => 'Error al acceder a los datos del corporativo (administracion: ' . $ex->getMessage()]);
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }

    /******************************************************************************************************************/
    // Vista que trabaja con solicitudes AJAX: Administracion de cargos
    /******************************************************************************************************************/
    public function getCargos(Request $request)
    {
        if($request->ajax()){
            $cargoLista = Cargo::all();
        }
        Log::info('Data de cargo: '.json_encode($cargoLista));
        return DataTables::of($cargoLista)
            ->addIndexColumn()
            ->make(true);
    }

    public function add_cargo(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'cargo' => 'required|string|unique:cargos,nombre',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => true, 'errors' => $validator->errors()]);
            }

            $cargo_name = $request->input('cargo');

            $cargo = new Cargo([
                'nombre'=>$cargo_name,
            ]);
            $cargo->save();

            Log::info('Cargo agregado correctamente: ['.$cargo->id.'] '.$cargo->nombre.' ');
            return response()->json(['success' => true, 'message' => 'Cargo agregado correctamente.']);
        }catch (\Exception $ex){
            Log::error('Error al registrar el cargo: '.$ex->getMessage());
            return response()->json(['error' => true, 'message' => 'Cargo no agregado.']);
        }
    }

    public function edit_cargo(int $id)
    {
        try{
            if(request()->ajax()){
                $cargo = Cargo::find($id);
                Log::info('Datos obtenidos del cargo: '.$cargo);
                return response()->json(['cargo'=>$cargo]);
            }
        }catch(\Exception $ex){
            Log::error(['error' => 'Error al acceder a los datos del cargo (administracion: ' . $ex->getMessage()]);
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }

    public function update_cargo(Request $request)
    {
        try{
            $id_cargo = $request->input('id_cargo');
            if ($id_cargo === null) {
                return response()->json(['error' => true, 'message' => 'ID del cargo no recibido'], 400);
            }
            $cargo_update = Cargo::findOrFail($id_cargo);
            Log::info('Cargo obtenido mediante el ID => '. json_encode($cargo_update));

            if (!$cargo_update) {
                return response()->json(['error' => true, 'message' => 'El cargo no se encontró en la base de datos'], 404);
            }

            $validator = Validator::make($request->all(), [
                'nombre_input_cargo' => 'required|unique:cargos,nombre',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => true, 'errors' => $validator->errors()]);
            }

            $nombre = $request->input('nombre_input_cargo');

            // actualizar datos:
            $cargo_update->nombre = $nombre;
            $cargo_update->save();

            Log::info('cargo actualizado: '.$cargo_update);

            return response()->json(['success' => true, 'message' => 'Area actualizado correctamente']);
        } catch (\Exception $ex) {
            Log::error('Error al actualizar el cargo: ' . $ex->getMessage());
            return response()->json(['error' => true, 'message' => 'Ocurrió un error al actualizar el cargo'], 500);
        }
    }

    public function delete_cargo(int $id)
    {
        try{
            if(request()->ajax()){
                $cargo = Cargo::findOrFail($id);
                Log::info('Datos obtenidos del cargo: '.$cargo);
                $cargo->delete();

                Log::info('Cargo eliminado: '.$cargo);
                return response()->json(['message' => 'El cargo ha sido eliminado correctamente']);
            }
        }catch(\Exception $ex){
            Log::error(['error' => 'Error al acceder a los datos del cargo (administracion: ' . $ex->getMessage()]);
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }

    /******************************************************************************************************************/
    // Vista que trabaja con solicitudes AJAX: Administracion de areas
    /******************************************************************************************************************/
    public function getAreas(Request $request)
    {
        if($request->ajax()){
            $areaLista = Area::all();
        }
        Log::info('Data de area: '.json_encode($areaLista));
        return DataTables::of($areaLista)
            ->addIndexColumn()
            ->make(true);
    }

    public function add_area(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'area' => 'required|string|unique:areas,nombre',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => true, 'errors' => $validator->errors()]);
            }
            $area_name = $request->input('area');

            $areas = new Area([
                'nombre'=>$area_name,
            ]);
            $areas->save();

            Log::info('Area agregada correctamente: ['.$areas->id.'] '.$areas->nombre.' ');

            return response()->json(['success' => true, 'message' => 'Area agregado correctamente.']);
        }catch (\Exception $ex){
            Log::error('Error al registrar el area: '.$ex->getMessage());
            return response()->json(['error' => true, 'message' => 'Area no agregado.']);
        }
    }

    public function edit_area(int $id)
    {
        try{
            if(request()->ajax()){
                $area = Area::find($id);
                Log::info('Datos obtenidos del area: '.$area);
                return response()->json(['area'=>$area]);
            }
        }catch(\Exception $ex){
            Log::error(['error' => 'Error al acceder a los datos del area (administracion: ' . $ex->getMessage()]);
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }

    public function update_area(Request $request)
    {
        try{
            $id_area = $request->input('id_area');
            if ($id_area === null) {
                return response()->json(['error' => true, 'message' => 'ID del area no recibido'], 400);
            }
            $area_update = Area::findOrFail($id_area);
            Log::info('Area obtenido mediante el ID => '. json_encode($area_update));

            if (!$area_update) {
                return response()->json(['error' => true, 'message' => 'El cargo no se encontró en la base de datos'], 404);
            }

            $validator = Validator::make($request->all(), [
                'nombre_input_area' => 'required|unique:areas,nombre',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => true, 'errors' => $validator->errors()]);
            }

            $nombre = $request->input('nombre_input_area');

            // actualizar datos:
            $area_update->nombre = $nombre;
            $area_update->save();

            Log::info('area actualizado: '.$area_update);

            return response()->json(['success' => true, 'message' => 'Area actualizado correctamente']);
        } catch (\Exception $ex) {
            Log::error('Error al actualizar el area: ' . $ex->getMessage());
            return response()->json(['error' => true, 'message' => 'Ocurrió un error al actualizar el area'], 500);
        }
    }

    public function delete_area(int $id)
    {
        try{
            if(request()->ajax()){
                $area = Area::findOrFail($id);
                Log::info('Datos obtenidos del area: '.$area);
                $area->delete();

                Log::info('Area eliminada: '.$area);
                return response()->json(['message' => 'El area ha sido eliminado correctamente']);
            }
        }catch(\Exception $ex){
            Log::error(['error' => 'Error al acceder a los datos del area (administracion: ' . $ex->getMessage()]);
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }

    /******************************************************************************************************************/
    /******************************************************************************************************************/

}
