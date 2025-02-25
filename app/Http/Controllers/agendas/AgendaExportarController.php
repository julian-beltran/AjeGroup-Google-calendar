<?php

namespace App\Http\Controllers\agendas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Area;
use App\Models\Espacio;

use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

//Para agregar estilos al excel exportado:
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\Storage;
use App\Models\Agenda;
use Illuminate\Support\Facades\Auth;
//para exportar
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

/*
 * Este controller contiene todos los métodos correspondientes a la administración de las agendas para generar
 * los reportes en formato excel y desde allí hacer seguimiento a los usuarios, también podrán ver las evidencias de las afendas.
*/

class AgendaExportarController extends Controller
{
    // Proteccion de rutas
    public function __construct(){
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->can('Ver agendas')
                && !Auth::user()->can('Ver invitaciones')
                && !Auth::user()->can('Ver reportes')) {
                    return response()->view('usuarios_vistas.users.error_403', [], 403);
            }
            return $next($request);
        });
    }

    public function index() {
        //lista de todas las areas:
        $areas = Area::all();
        $espacios = Espacio::all();

        return view('usuarios_vistas.reportes.lista_agendas_para_exportar', ['areas'=>$areas, 'espacios'=>$espacios]);
    }

    // Método para visualizar todas las agendas en general implementando un filtro por default de "Pendiente"
    public function ver_agendas_para_exportar(Request $request)
    {
        $query = DB::table('agenda_invitados')
            ->select(
                'agendas.id AS agenda_id',
                'agenda_invitados.id_agenda AS id',
                'areas.id AS area',
                'areas.nombre AS area_nombre',
                'espacios.id AS id_espacio',
                'espacios.nombre AS espacio',
                'agendas.fecha_hora_meet AS fecha_hora',
                'agendas.estado AS estado',
                DB::raw('(SELECT name FROM users WHERE users.id = agendas.id_user) AS anfitrion'),
                DB::raw('(SELECT name FROM users WHERE users.id = agenda_invitados.id_user) AS invitado')
            )
            ->join('agendas', 'agendas.id', '=', 'agenda_invitados.id_agenda')
            ->join('users', 'users.id', '=', 'agenda_invitados.id_user')
            ->join('areas', 'areas.id', '=', 'agendas.id_area')
            ->join('espacios', 'espacios.id', '=', 'agendas.id_espacio') ;

        if($request->estado != ''){
            $query->where('agendas.estado', $request->estado);
        }
        if($request->fecha_desde != '' && $request->fecha_hasta != ''){
            $query->whereBetween('agendas.fecha_hora_meet', [$request->fecha_desde, $request->fecha_hasta]);
        }
        if($request->espacio != ''){
            $query->where('espacios.id', $request->espacio);
        }
        if($request->area != ''){
            $query->where('areas.id', $request->area);
        }

        $data = $query;

        return DataTables::of($data)
            ->addIndexColumn()
            ->toJson();
    }

    // Método para exportar las agendas en formato excel según el filtro que se realice:
    public function exportar_agendas_excel(Request $request)
    {
        try {
            $data = [];

            $query = DB::table('agenda_invitados')
                ->select(
                    'agenda_invitados.id_agenda AS id', 'areas.id AS area', 'areas.nombre AS area_nombre', 'espacios.id AS id_espacio', 'espacios.nombre AS espacio', 'agendas.fecha_hora_meet AS fecha_hora', 'agendas.estado AS estado',
                    DB::raw('(SELECT name FROM users WHERE users.id = agendas.id_user) AS anfitrion'),
                    DB::raw('(SELECT name FROM users WHERE users.id = agenda_invitados.id_user) AS invitado')
                )
                ->join('agendas', 'agendas.id', '=', 'agenda_invitados.id_agenda')
                ->join('users', 'users.id', '=', 'agenda_invitados.id_user')
                ->join('areas', function($join) {
                    $join->on('areas.id', '=', 'agenda_invitados.id_area')
                        ->orWhereColumn('areas.id', '=', 'agendas.id_area');
                })
                ->join('espacios', 'espacios.id', '=', 'agendas.id_espacio') ;

            if($request->estado != ''){
                $query->where('agendas.estado', $request->estado);
            }
            if($request->fecha_desde != '' && $request->fecha_hasta != ''){
                $query->whereBetween('agendas.fecha_hora_meet', [$request->fecha_desde, $request->fecha_hasta]);
            }
            if($request->espacio != ''){
                $query->where('espacios.id', $request->espacio);
            }
            if($request->area != ''){
                $query->where('areas.id', $request->area);
            }

            $data = $query->get();

            // Creando una instancia de PhpSpreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Definición de estilos para la cabecera
            $headerStyle = [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '000000']],
            ];

            // Aplicar estilos a la cabecera
            $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);

            $sheet->setCellValue('A1', 'ID AGENDA');
            $sheet->setCellValue('B1', 'ID AREA');
            $sheet->setCellValue('C1', 'AREA');
            $sheet->setCellValue('D1', 'ID ESPACIO');
            $sheet->setCellValue('E1', 'ESPACIO');
            $sheet->setCellValue('F1', 'FECHA/HORA meet');
            $sheet->setCellValue('G1', 'ANFITRION');
            $sheet->setCellValue('H1', 'INVITADO');
            $sheet->setCellValue('I1', 'ESTADO');

            // Llamada de los datos en el archivo excel
            $row = 2;
            foreach ($data as $item) {
                $sheet->setCellValue('A' . $row, $item->id);
                $sheet->setCellValue('B' . $row, $item->area);
                $sheet->setCellValue('C' . $row, $item->area_nombre);
                $sheet->setCellValue('D' . $row, $item->id_espacio);
                $sheet->setCellValue('E' . $row, $item->espacio);
                $sheet->setCellValue('F' . $row, $item->fecha_hora);
                $sheet->setCellValue('G' . $row, $item->anfitrion);
                $sheet->setCellValue('H' . $row, $item->invitado);
                $sheet->setCellValue('I' . $row, $item->estado);

                // Aplicar color de texto según el estado
                $estadoCell = $sheet->getCell('I' . $row);
                if ($item->estado === 'pendiente') {
                    $estadoCell->getStyle()->getFont()->getColor()->setRGB('FF0000'); // Rojo para estado pendiente
                } elseif ($item->estado === 'terminado') {
                    $estadoCell->getStyle()->getFont()->getColor()->setRGB('00FF00'); // Verde para estado terminado
                }

                $row++;
            }

            // Ajustar el ancho de las columnas automáticamente
            foreach (range('A', 'I') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
            // Crear un objeto Response con el contenido del archivo
            $response = new StreamedResponse(function () use ($spreadsheet) {
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
            });

            // Configurar las cabeceras de la respuesta
            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $response->headers->set('Content-Disposition', 'attachment; filename="agendas_programadas.xlsx"');
            $response->headers->set('Cache-Control', 'max-age=0');

            return $response;
        } catch (\Exception $ex) {
            Log::error('Error en exportExcel: ' . $ex->getMessage());
            return response()->json(['error' => 'Error interno']);
        }
    }
    // Método para visualizar la evidencia de la agenda en un modal con AJAX:
    public function show_evidencia_agenda_exportar($id) {
        // Obtener la agenda con los archivos asociados
        $agenda = Agenda::select('ag.id', 'us.name as user_name', 'agar.archivos as archivos', 'ag.fecha_hora_meet')
            ->from('agendas as ag')
            ->join('agenda_archivos as agar', 'agar.id_agenda', '=', 'ag.id')
            ->join('users as us', 'us.id', '=', 'agar.id_user')
            ->where('ag.id', $id)
            ->first();

        if (!$agenda) {
            return response()->json(['error' => 'No se encontró la agenda solicitada.']);
        }

        // Decodificar el JSON de archivos
        $archivos = json_decode($agenda->archivos, true);

        // Preparando la lista de descargables
        $descargables = [];
        foreach ($archivos as $nombreArchivo) {
            $urlDescarga = '/'.$nombreArchivo; // Storage::url($nombreArchivo);
            $descargables[] = ['nombre' => $nombreArchivo, 'url' => $urlDescarga];
        }

        return response()->json(['agenda' => $agenda, 'descargables' => $descargables]);
    }


    // Exportar agendas desde modulo -> ESPACIOS: todas | agendadas | atendidas | concluidas
    public function exportar_todas_excel(Request $request)
    {
        Log::info('Export_agendas_general: '.json_encode($request->all()));
        try {
            $data = [];

            $id_esp = $request->espacio;
            $espacio_data = Espacio::find($id_esp);
            $espacio_id = $espacio_data->id;

            $usuario    = Auth::user();
            $idUserLog  = $usuario->id;

            $todasQuery = DB::table('agenda_invitados')
                ->select(
                    'agenda_invitados.id_agenda AS id', 'areas.id AS area', 'areas.nombre AS area_nombre',
                    'espacios.id AS id_espacio', 'espacios.nombre AS espacio', 'agendas.fecha_hora_meet AS fecha_hora', 'agendas.estado AS estado',
                    DB::raw('(SELECT name FROM users WHERE users.id = agendas.id_user) AS anfitrion'),
                    DB::raw('(SELECT name FROM users WHERE users.id = agenda_invitados.id_user) AS invitado')
                )
                ->join('agendas', 'agendas.id', '=', 'agenda_invitados.id_agenda')
                ->join('users', 'users.id', '=', 'agenda_invitados.id_user')
                ->join('areas', function($join) {
                    $join->on('areas.id', '=', 'agenda_invitados.id_area')
                        ->orWhereColumn('areas.id', '=', 'agendas.id_area');
                })
                ->join('espacios', 'espacios.id', '=', 'agendas.id_espacio')
                ->where('espacios.id', $espacio_id);

            if($request->area != ''){
                $todasQuery->where('areas.id', $request->area);
            }
            if($request->fecha != ''){
                $todasQuery->whereDate('agendas.fecha_hora_meet', $request->fecha);
            }
            if($request->invitado != ''){
                $todasQuery->where('agenda_invitados.id_user', $request->invitado);
            }
            $data = $todasQuery->get();

            Log::info('Datos obtenidos para la exportacion: '.json_encode($data));

            // Creando una instancia de PhpSpreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Definición de estilos para la cabecera
            $headerStyle = [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '000000']],
            ];

            // Aplicar estilos a la cabecera
            $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);

            $sheet->setCellValue('A1', 'ID AGENDA');
            $sheet->setCellValue('B1', 'ID AREA');
            $sheet->setCellValue('C1', 'AREA');
            $sheet->setCellValue('D1', 'ID ESPACIO');
            $sheet->setCellValue('E1', 'ESPACIO');
            $sheet->setCellValue('F1', 'FECHA/HORA meet');
            $sheet->setCellValue('G1', 'ANFITRION');
            $sheet->setCellValue('H1', 'INVITADO');
            $sheet->setCellValue('I1', 'ESTADO');

            // Llamada de los datos en el archivo excel
            $row = 2;
            foreach ($data as $item) {
                $sheet->setCellValue('A' . $row, $item->id);
                $sheet->setCellValue('B' . $row, $item->area);
                $sheet->setCellValue('C' . $row, $item->area_nombre);
                $sheet->setCellValue('D' . $row, $item->id_espacio);
                $sheet->setCellValue('E' . $row, $item->espacio);
                $sheet->setCellValue('F' . $row, $item->fecha_hora);
                $sheet->setCellValue('G' . $row, $item->anfitrion);
                $sheet->setCellValue('H' . $row, $item->invitado);
                $sheet->setCellValue('I' . $row, $item->estado);

                // Aplicar color de texto según el estado
                $estadoCell = $sheet->getCell('I' . $row);
                if ($item->estado === 'pendiente') {
                    $estadoCell->getStyle()->getFont()->getColor()->setRGB('FF0000'); // Rojo para estado pendiente
                } elseif ($item->estado === 'terminado') {
                    $estadoCell->getStyle()->getFont()->getColor()->setRGB('00FF00'); // Verde para estado terminado
                }

                $row++;
            }

            // Ajustar el ancho de las columnas automáticamente
            foreach (range('A', 'I') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            Log::info('Datos capturados para exportar: '.json_encode($data));

            // Crear un objeto Response con el contenido del archivo
            $response = new StreamedResponse(function () use ($spreadsheet) {
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
            });

            // Configurar las cabeceras de la respuesta
            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $response->headers->set('Content-Disposition', 'attachment; filename="todas_las_agendas.xlsx"');
            $response->headers->set('Cache-Control', 'max-age=0');

            return $response;
        } catch (\Exception $ex) {
            Log::error('Error en exportExcel: ' . $ex->getMessage());
            return response()->json(['error' => 'Error interno']);
        }
    }
    public function exportar_agendadas_excel(Request $request)
    {
        Log::info('Exportar agendas_agendadas: '.json_encode($request->all()));
        try {
            $data = [];

            $id_esp = $request->espacio;
            $espacio_data = Espacio::find($id_esp);
            $espacio_id = $espacio_data->id;

            $usuario    = Auth::user();
            $idUserLog  = $usuario->id;

            $todasQuery = DB::table('agenda_invitados')
                ->select(
                    'agenda_invitados.id_agenda AS id', 'areas.id AS area', 'areas.nombre AS area_nombre',
                    'espacios.id AS id_espacio', 'espacios.nombre AS espacio', 'agendas.fecha_hora_meet AS fecha_hora', 'agendas.estado AS estado',
                    DB::raw('(SELECT name FROM users WHERE users.id = agendas.id_user) AS anfitrion'),
                    DB::raw('(SELECT name FROM users WHERE users.id = agenda_invitados.id_user) AS invitado')
                )
                ->join('agendas', 'agendas.id', '=', 'agenda_invitados.id_agenda')
                ->join('users', 'users.id', '=', 'agenda_invitados.id_user')
                ->join('areas', function($join) {
                    $join->on('areas.id', '=', 'agenda_invitados.id_area')
                        ->orWhereColumn('areas.id', '=', 'agendas.id_area');
                })
                ->join('espacios', 'espacios.id', '=', 'agendas.id_espacio')
                ->where('espacios.id', $espacio_id)
                ->where('agendas.fecha_hora_meet', '>=', now());

            if($request->area != ''){
                $todasQuery->where('areas.id', $request->area);
            }
            if($request->fecha != ''){
                $todasQuery->whereDate('agendas.fecha_hora_meet', $request->fecha);
            }
            if($request->invitado != ''){
                $todasQuery->where('agenda_invitados.id_user', $request->invitado);
            }

            $data = $todasQuery->get();

            Log::info('Datos obtenidos para la exportacion: '.json_encode($data));

            // Creando una instancia de PhpSpreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Definición de estilos para la cabecera
            $headerStyle = [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '000000']],
            ];

            // Aplicar estilos a la cabecera
            $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);

            $sheet->setCellValue('A1', 'ID AGENDA');
            $sheet->setCellValue('B1', 'ID AREA');
            $sheet->setCellValue('C1', 'AREA');
            $sheet->setCellValue('D1', 'ID ESPACIO');
            $sheet->setCellValue('E1', 'ESPACIO');
            $sheet->setCellValue('F1', 'FECHA/HORA meet');
            $sheet->setCellValue('G1', 'ANFITRION');
            $sheet->setCellValue('H1', 'INVITADO');
            $sheet->setCellValue('I1', 'ESTADO');

            // Llamada de los datos en el archivo excel
            $row = 2;
            foreach ($data as $item) {
                $sheet->setCellValue('A' . $row, $item->id);
                $sheet->setCellValue('B' . $row, $item->area);
                $sheet->setCellValue('C' . $row, $item->area_nombre);
                $sheet->setCellValue('D' . $row, $item->id_espacio);
                $sheet->setCellValue('E' . $row, $item->espacio);
                $sheet->setCellValue('F' . $row, $item->fecha_hora);
                $sheet->setCellValue('G' . $row, $item->anfitrion);
                $sheet->setCellValue('H' . $row, $item->invitado);
                $sheet->setCellValue('I' . $row, $item->estado);

                // Aplicar color de texto según el estado
                $estadoCell = $sheet->getCell('I' . $row);
                if ($item->estado === 'pendiente') {
                    $estadoCell->getStyle()->getFont()->getColor()->setRGB('FF0000'); // Rojo para estado pendiente
                } elseif ($item->estado === 'terminado') {
                    $estadoCell->getStyle()->getFont()->getColor()->setRGB('00FF00'); // Verde para estado terminado
                }

                $row++;
            }

            // Ajustar el ancho de las columnas automáticamente
            foreach (range('A', 'I') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            Log::info('Datos capturados para exportar: '.json_encode($data));

            // Crear un objeto Response con el contenido del archivo
            $response = new StreamedResponse(function () use ($spreadsheet) {
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
            });

            // Configurar las cabeceras de la respuesta
            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $response->headers->set('Content-Disposition', 'attachment; filename="agendas_programadas.xlsx"');
            $response->headers->set('Cache-Control', 'max-age=0');

            return $response;
        } catch (\Exception $ex) {
            Log::error('Error en exportExcel: ' . $ex->getMessage());
            return response()->json(['error' => 'Error interno']);
        }
    }
    public function exportar_atendidas_excel(Request $request)
    {
        Log::info('Export_agendas_atendidas: '.json_encode($request->all()));
        try {
            $data = [];
            $id_esp = $request->espacio;
            $espacio_data = Espacio::find($id_esp);
            $espacio_id = $espacio_data->id;

            $usuario    = Auth::user();
            $idUserLog  = $usuario->id;

            $todasQuery = DB::table('agenda_invitados')
                ->select(
                    'agenda_invitados.id_agenda AS id', 'areas.id AS area', 'areas.nombre AS area_nombre', 'espacios.id AS id_espacio',
                    'espacios.nombre AS espacio', 'agendas.fecha_hora_meet AS fecha_hora', 'agendas.estado AS estado',
                    DB::raw('(SELECT name FROM users WHERE users.id = agendas.id_user) AS anfitrion'),
                    DB::raw('(SELECT name FROM users WHERE users.id = agenda_invitados.id_user) AS invitado')
                )
                ->join('agendas', 'agendas.id', '=', 'agenda_invitados.id_agenda')
                ->join('users', 'users.id', '=', 'agenda_invitados.id_user')
                ->join('areas', function($join) {
                    $join->on('areas.id', '=', 'agenda_invitados.id_area')
                        ->orWhereColumn('areas.id', '=', 'agendas.id_area');
                })
                ->join('espacios', 'espacios.id', '=', 'agendas.id_espacio')
                ->where('espacios.id', $espacio_id)
                ->where('agendas.fecha_hora_meet', '<', now())
                ->where('agendas.estado', '=', 'pendiente');

            if($request->area != ''){
                $todasQuery->where('areas.id', $request->area);
            }
            if($request->fecha != ''){
                $todasQuery->whereDate('agendas.fecha_hora_meet', $request->fecha);
            }
            if($request->invitado != ''){
                $todasQuery->where('agenda_invitados.id_user', $request->invitado);
            }
            $data = $todasQuery->get();

            Log::info('Datos obtenidos para la exportacion: '.json_encode($data));

            // Creando una instancia de PhpSpreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Definición de estilos para la cabecera
            $headerStyle = [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '000000']],
            ];

            // Aplicar estilos a la cabecera
            $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);

            $sheet->setCellValue('A1', 'ID AGENDA');
            $sheet->setCellValue('B1', 'ID AREA');
            $sheet->setCellValue('C1', 'AREA');
            $sheet->setCellValue('D1', 'ID ESPACIO');
            $sheet->setCellValue('E1', 'ESPACIO');
            $sheet->setCellValue('F1', 'FECHA/HORA meet');
            $sheet->setCellValue('G1', 'ANFITRION');
            $sheet->setCellValue('H1', 'INVITADO');
            $sheet->setCellValue('I1', 'ESTADO');

            // Llamada de los datos en el archivo excel
            $row = 2;
            foreach ($data as $item) {
                $sheet->setCellValue('A' . $row, $item->id);
                $sheet->setCellValue('B' . $row, $item->area);
                $sheet->setCellValue('C' . $row, $item->area_nombre);
                $sheet->setCellValue('D' . $row, $item->id_espacio);
                $sheet->setCellValue('E' . $row, $item->espacio);
                $sheet->setCellValue('F' . $row, $item->fecha_hora);
                $sheet->setCellValue('G' . $row, $item->anfitrion);
                $sheet->setCellValue('H' . $row, $item->invitado);
                $sheet->setCellValue('I' . $row, $item->estado);

                // Aplicar color de texto según el estado
                $estadoCell = $sheet->getCell('I' . $row);
                if ($item->estado === 'pendiente') {
                    $estadoCell->getStyle()->getFont()->getColor()->setRGB('FF0000'); // Rojo para estado pendiente
                } elseif ($item->estado === 'terminado') {
                    $estadoCell->getStyle()->getFont()->getColor()->setRGB('00FF00'); // Verde para estado terminado
                }

                $row++;
            }

            // Ajustar el ancho de las columnas automáticamente
            foreach (range('A', 'I') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
            // Log::info('Datos capturados para exportar: '.json_encode($data));
            // Crear un objeto Response con el contenido del archivo
            $response = new StreamedResponse(function () use ($spreadsheet) {
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
            });

            // Configurar las cabeceras de la respuesta
            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $response->headers->set('Content-Disposition', 'attachment; filename="agendas_atendidas.xlsx"');
            $response->headers->set('Cache-Control', 'max-age=0');

            return $response;
        } catch (\Exception $ex) {
            Log::error('Error en exportExcel: ' . $ex->getMessage());
            return response()->json(['error' => 'Error interno']);
        }
    }
    public function exportar_concluidas_excel(Request $request)
    {
        Log::info('Export agendas_concluidas: '.json_encode($request->all()));
        try {
            $data = [];

            $id_esp = $request->espacio;
            $espacio_data = Espacio::find($id_esp);
            $espacio_id = $espacio_data->id;

            $usuario    = Auth::user();
            $idUserLog  = $usuario->id;

            $todasQuery = DB::table('agenda_invitados')
                ->select(
                    'agenda_invitados.id_agenda AS id', 'areas.id AS area', 'areas.nombre AS area_nombre',
                    'espacios.id AS id_espacio', 'espacios.nombre AS espacio', 'agendas.fecha_hora_meet AS fecha_hora', 'agendas.estado AS estado',
                    DB::raw('(SELECT name FROM users WHERE users.id = agendas.id_user) AS anfitrion'),
                    DB::raw('(SELECT name FROM users WHERE users.id = agenda_invitados.id_user) AS invitado')
                )
                ->join('agendas', 'agendas.id', '=', 'agenda_invitados.id_agenda')
                ->join('users', 'users.id', '=', 'agenda_invitados.id_user')
                ->join('areas', function($join) {
                    $join->on('areas.id', '=', 'agenda_invitados.id_area')
                        ->orWhereColumn('areas.id', '=', 'agendas.id_area');
                })
                ->join('espacios', 'espacios.id', '=', 'agendas.id_espacio')
                ->where('espacios.id', $espacio_id)
                ->where('agendas.estado', '=', 'terminado');

            if($request->area != ''){
                $todasQuery->where('areas.id', $request->area);
            }
            if($request->fecha != ''){
                $todasQuery->whereDate('agendas.fecha_hora_meet', $request->fecha);
            }
            if($request->invitado != ''){
                $todasQuery->where('agenda_invitados.id_user', $request->invitado);
            }
            $data = $todasQuery->get();

            Log::info('Datos obtenidos para la exportacion: '.json_encode($data));

            // Creando una instancia de PhpSpreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Definición de estilos para la cabecera
            $headerStyle = [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '000000']],
            ];

            // Aplicar estilos a la cabecera
            $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);

            $sheet->setCellValue('A1', 'ID AGENDA');
            $sheet->setCellValue('B1', 'ID AREA');
            $sheet->setCellValue('C1', 'AREA');
            $sheet->setCellValue('D1', 'ID ESPACIO');
            $sheet->setCellValue('E1', 'ESPACIO');
            $sheet->setCellValue('F1', 'FECHA/HORA meet');
            $sheet->setCellValue('G1', 'ANFITRION');
            $sheet->setCellValue('H1', 'INVITADO');
            $sheet->setCellValue('I1', 'ESTADO');

            // Llamada de los datos en el archivo excel
            $row = 2;
            foreach ($data as $item) {
                $sheet->setCellValue('A' . $row, $item->id);
                $sheet->setCellValue('B' . $row, $item->area);
                $sheet->setCellValue('C' . $row, $item->area_nombre);
                $sheet->setCellValue('D' . $row, $item->id_espacio);
                $sheet->setCellValue('E' . $row, $item->espacio);
                $sheet->setCellValue('F' . $row, $item->fecha_hora);
                $sheet->setCellValue('G' . $row, $item->anfitrion);
                $sheet->setCellValue('H' . $row, $item->invitado);
                $sheet->setCellValue('I' . $row, $item->estado);

                // Aplicar color de texto según el estado
                $estadoCell = $sheet->getCell('I' . $row);
                if ($item->estado === 'pendiente') {
                    $estadoCell->getStyle()->getFont()->getColor()->setRGB('FF0000'); // Rojo para estado pendiente
                } elseif ($item->estado === 'terminado') {
                    $estadoCell->getStyle()->getFont()->getColor()->setRGB('00FF00'); // Verde para estado terminado
                }

                $row++;
            }

            // Ajustar el ancho de las columnas automáticamente
            foreach (range('A', 'I') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            Log::info('Datos capturados para exportar: '.json_encode($data));

            // Crear un objeto Response con el contenido del archivo
            $response = new StreamedResponse(function () use ($spreadsheet) {
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
            });

            // Configurar las cabeceras de la respuesta
            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $response->headers->set('Content-Disposition', 'attachment; filename="agendas_concluidas.xlsx"');
            $response->headers->set('Cache-Control', 'max-age=0');

            return $response;
        } catch (\Exception $ex) {
            Log::error('Error en exportExcel: ' . $ex->getMessage());
            return response()->json(['error' => 'Error interno']);
        }
    }
    // Exportar agendas desde modulo: ESTADO_SESIONES
    public function exportar_atendidas_general_excel(Request $request)
    {
        Log::info('request export estado_sesiones_atendidas: '.json_encode($request->all()));
        try {
            $data = [];

            $usuario    = Auth::user();
            $idUserLog  = $usuario->id;

            $todasQuery = DB::table('agenda_invitados')
                ->select(
                    'agenda_invitados.id_agenda AS id',
                    'areas.id AS area',
                    'areas.nombre AS area_nombre',
                    'espacios.id AS id_espacio',
                    'espacios.nombre AS espacio',
                    'agendas.fecha_hora_meet AS fecha_hora',
                    'agendas.estado AS estado',
                    DB::raw('(SELECT name FROM users WHERE users.id = agendas.id_user) AS anfitrion'),
                    DB::raw('(SELECT name FROM users WHERE users.id = agenda_invitados.id_user) AS invitado')
                )
                ->join('agendas', 'agendas.id', '=', 'agenda_invitados.id_agenda')
                ->join('users', 'users.id', '=', 'agenda_invitados.id_user')
                ->join('areas', function($join) {
                    $join->on('areas.id', '=', 'agenda_invitados.id_area')
                        ->orWhereColumn('areas.id', '=', 'agendas.id_area');
                })
                ->join('espacios', 'espacios.id', '=', 'agendas.id_espacio')
                // ->where('espacios.id', $espacio_id)
                ->where('agendas.fecha_hora_meet', '<', now())
                ->where('agendas.estado', '=', 'pendiente');

            if($request->area != ''){
                $todasQuery->where('areas.id', $request->area);
            }
            if($request->fecha != ''){
                $todasQuery->whereDate('agendas.fecha_hora_meet', $request->fecha);
            }
            if($request->invitado != ''){
                $todasQuery->where('agenda_invitados.id_user', $request->invitado);
            }

            $data = $todasQuery->get();

            Log::info('Datos para exportacion en estado_sesiones_atendidas: '.json_encode($data));

            // Creando una instancia de PhpSpreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Definición de estilos para la cabecera
            $headerStyle = [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '000000']],
            ];

            // Aplicar estilos a la cabecera
            $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);

            $sheet->setCellValue('A1', 'ID AGENDA');
            $sheet->setCellValue('B1', 'ID AREA');
            $sheet->setCellValue('C1', 'AREA');
            $sheet->setCellValue('D1', 'ID ESPACIO');
            $sheet->setCellValue('E1', 'ESPACIO');
            $sheet->setCellValue('F1', 'FECHA/HORA meet');
            $sheet->setCellValue('G1', 'ANFITRION');
            $sheet->setCellValue('H1', 'INVITADO');
            $sheet->setCellValue('I1', 'ESTADO');

            // Llamada de los datos en el archivo excel
            $row = 2;
            foreach ($data as $item) {
                $sheet->setCellValue('A' . $row, $item->id);
                $sheet->setCellValue('B' . $row, $item->area);
                $sheet->setCellValue('C' . $row, $item->area_nombre);
                $sheet->setCellValue('D' . $row, $item->id_espacio);
                $sheet->setCellValue('E' . $row, $item->espacio);
                $sheet->setCellValue('F' . $row, $item->fecha_hora);
                $sheet->setCellValue('G' . $row, $item->anfitrion);
                $sheet->setCellValue('H' . $row, $item->invitado);
                $sheet->setCellValue('I' . $row, $item->estado);

                // Aplicar color de texto según el estado
                $estadoCell = $sheet->getCell('I' . $row);
                if ($item->estado === 'pendiente') {
                    $estadoCell->getStyle()->getFont()->getColor()->setRGB('FF0000'); // Rojo para estado pendiente
                } elseif ($item->estado === 'terminado') {
                    $estadoCell->getStyle()->getFont()->getColor()->setRGB('00FF00'); // Verde para estado terminado
                }

                $row++;
            }

            // Ajustar el ancho de las columnas automáticamente
            foreach (range('A', 'I') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Log::info('Datos capturados para exportar: '.json_encode($data));

            // Crear un objeto Response con el contenido del archivo
            $response = new StreamedResponse(function () use ($spreadsheet) {
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
            });

            // Configurar las cabeceras de la respuesta
            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $response->headers->set('Content-Disposition', 'attachment; filename="agendas_atendidas_general.xlsx"');
            $response->headers->set('Cache-Control', 'max-age=0');

            return $response;
        } catch (\Exception $ex) {
            Log::error('Error en exportExcel: ' . $ex->getMessage());
            return response()->json(['error' => 'Error interno']);
        }
    }

}
