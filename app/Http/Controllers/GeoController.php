<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GeoController extends Controller
{
    /**
     * دریافت شهرهای یک استان (برای انتخاب شهر وابسته در فرم‌ها)
     */
    public function cities(Request $request): JsonResponse
    {
        $provinceId = $request->query('province_id');

        $cities = City::where('province_id', $provinceId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($cities);
    }
}
