<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SensorColmena;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SensorColmenaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $registros = SensorColmena::orderBy('id_sensor')->get();

        return response()->json(['total' => $registros->count(), 'data' => $registros], 200);
    }

    public function show(string $id): JsonResponse
    {
        $registro = SensorColmena::find($id);

        if (!$registro) {
            return response()->json(['message' => 'Registro de colmena no encontrado.'], 404);
        }

        return response()->json($registro, 200);
    }

    public function store(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'apiario'     => 'required|string|max:100',
            'temperatura' => 'required|numeric|min:-50|max:100',
            'humedad'     => 'required|numeric|min:0|max:100',
        ], [
            'apiario.required'     => 'El apiario es obligatorio.',
            'temperatura.required' => 'La temperatura es obligatoria.',
            'temperatura.numeric'  => 'La temperatura debe ser un número.',
            'humedad.required'     => 'La humedad es obligatoria.',
            'humedad.numeric'      => 'La humedad debe ser un número.',
        ]);

        $temperatura = (float) $datos['temperatura'];

        if ($temperatura < 32.0) {
            $datos['mensaje_alerta'] = 'ALERTA: ENFRIAMIENTO DE CRIA';
        } elseif ($temperatura <= 36.0) {
            $datos['mensaje_alerta'] = 'MICROCLIMA OPTIMO';
        } else {
            $datos['mensaje_alerta'] = 'ALERTA: ESTRES TERMICO';
        }

        $registro = SensorColmena::create($datos);

        return response()->json($registro, 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $registro = SensorColmena::find($id);

        if (!$registro) {
            return response()->json(['message' => 'Registro de colmena no encontrado.'], 404);
        }

        $datos = $request->validate([
            'apiario'     => 'required|string|max:100',
            'temperatura' => 'required|numeric|min:-50|max:100',
            'humedad'     => 'required|numeric|min:0|max:100',
        ], [
            'apiario.required'     => 'El apiario es obligatorio.',
            'temperatura.required' => 'La temperatura es obligatoria.',
            'temperatura.numeric'  => 'La temperatura debe ser un número.',
            'humedad.required'     => 'La humedad es obligatoria.',
            'humedad.numeric'      => 'La humedad debe ser un número.',
        ]);

        $temperatura = (float) $datos['temperatura'];

        if ($temperatura < 32.0) {
            $datos['mensaje_alerta'] = 'ALERTA: ENFRIAMIENTO DE CRIA';
        } elseif ($temperatura <= 36.0) {
            $datos['mensaje_alerta'] = 'MICROCLIMA OPTIMO';
        } else {
            $datos['mensaje_alerta'] = 'ALERTA: ESTRES TERMICO';
        }

        $registro->update($datos);

        return response()->json($registro, 200);
    }

    public function destroy(string $id): JsonResponse
    {
        $registro = SensorColmena::find($id);

        if (!$registro) {
            return response()->json(['message' => 'Registro de colmena no encontrado.'], 404);
        }

        $registro->delete();

        return response()->json(['message' => 'Registro eliminado correctamente.'], 200);
    }
}