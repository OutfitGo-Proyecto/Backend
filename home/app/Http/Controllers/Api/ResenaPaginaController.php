<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ResenaPagina;
use Illuminate\Http\JsonResponse;

class ResenaPaginaController extends Controller
{
    public function index(): JsonResponse
    {
        $resenas = ResenaPagina::with('user:id,name')
            ->where('visible_en_portada', true)
            ->latest()
            ->take(3)
            ->get();

        return response()->json($resenas, 200);
    }
}
