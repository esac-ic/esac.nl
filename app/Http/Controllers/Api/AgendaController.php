<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AgendaItemCategoryResource;
use App\Http\Resources\AgendaItemResource;
use App\Models\AgendaItem;
use App\Models\AgendaItemCategory;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function getAgenda(Request $request): JsonResponse
    {
        $request->validate([
            'startDate' => ['nullable', 'date_format:d-m-Y'],
            'endDate' => ['nullable', 'date_format:d-m-Y'],
        ]);

        $agendaItemQuery = AgendaItem::with(['agendaItemCategory']);

        $limit = $request->input('limit', 9);
        $start = $request->input('start', 0);

        // Apply filters
        if ($category = $request->input('category')) {
            $agendaItemQuery->where('category', intval($category));
        }

        if ($startDate = $request->input('startDate')) {
            $startDate = Carbon::createFromFormat('d-m-Y', $startDate)->startOfDay();
            $agendaItemQuery->where(function ($query) use ($startDate) {
                $query->where('start_date', '>=', $startDate)
                    ->orWhere('end_date', '>=', $startDate);
            });
        }

        if ($endDate = $request->input('endDate')) {
            $endDate = Carbon::createFromFormat('d-m-Y', $endDate)->endOfDay();
            $agendaItemQuery->where('start_date', '<=', $endDate);
        }

        $agendaItemQuery->orderBy('start_date', 'asc');

        $agendaItemCount = $agendaItemQuery->count();

        $agendaItems = $agendaItemQuery
            ->skip($start)
            ->take($limit)
            ->get();

        return response()->json([
            'agendaItemCount' => $agendaItemCount,
            'agendaItems' => AgendaItemResource::collection($agendaItems),
        ]);
    }

    public function getCategories(): JsonResponse
    {
        return response()->json(AgendaItemCategoryResource::collection(AgendaItemCategory::all()));
    }
}
