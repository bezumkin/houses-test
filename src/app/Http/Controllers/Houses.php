<?php

namespace App\Http\Controllers;

use App\Models\House;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Houses extends Controller
{
    public function getHouses(Request $request): LengthAwarePaginator
    {
        $condition = House::query();

        if ($request->has('query')) {
            $condition->where('title', 'LIKE', '%' . $request->get('query') . '%');
        }
        if ($request->has('price')) {
            $condition->whereBetween('price', explode(',', $request->get('price')));
        }

        $keys = ['bedrooms', 'bathrooms', 'storeys', 'garages'];
        foreach ($keys as $key) {
            if ($request->has($key)) {
                $condition->where($key, $request->get($key));
            }
        }

        if ($request->has('sort')) {
            $condition->orderBy($request->get('sort'), $request->get('dir') === 'descending' ? 'desc' : 'asc');
        }
        $limit = $request->get('limit') ?: 1000;

        return $condition->paginate($limit);
    }

    public function getFilters(): JsonResponse
    {
        $filters = [
            'price' => [
                'min' => House::query()->min('price'),
                'max' => House::query()->max('price'),
            ],
        ];

        $keys = ['bedrooms', 'bathrooms', 'storeys', 'garages'];
        foreach ($keys as $key) {
            $filters[$key] = House::query()->orderBy($key)->select($key)->distinct()->pluck($key)->toArray();
        }

        return response()->json($filters);
    }
}
