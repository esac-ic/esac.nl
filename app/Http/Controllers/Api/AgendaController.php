<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AgendaItemCategoryResource;
use App\Http\Resources\AgendaItemResource;
use App\Models\AgendaItem;
use App\Models\AgendaItemCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function getAgenda(Request $request): JsonResponse
    {
        $request->validate([
            'startDate' => ['nullable', 'date_format:d-m-Y'],
            'endDate' => ['nullable', 'date_format:d-m-Y'],
            'start' => ['nullable', 'integer', 'min:0'],
            // The admin agenda table loads every event at once.
            'limit' => ['nullable', 'integer', 'min:1'],
            'category' => ['nullable', 'integer'],
        ]);

        $agendaItemQuery = AgendaItem::query()
            ->with([
                'agendaItemCategory',
                'getApplicationForm',
                // Only the current user's registration, to show whether they signed up.
                'getApplicationFormResponses' => fn ($query) => $query->where('user_id', $request->user()?->id),
            ])
            ->withCount('getApplicationFormResponses');

        $limit = $request->integer('limit', 9);
        $start = $request->integer('start');

        // Apply filters
        if ($category = $request->integer('category')) {
            $agendaItemQuery->where('category', $category);
        }

        if ($startDate = $request->date('startDate', 'd-m-Y', config('app.display_timezone'))?->startOfDay()->utc()) {
            $agendaItemQuery->where(function ($query) use ($startDate) {
                $query->where('start_date', '>=', $startDate)
                    ->orWhere('end_date', '>=', $startDate);
            });
        }

        if ($endDate = $request->date('endDate', 'd-m-Y', config('app.display_timezone'))?->endOfDay()->utc()) {
            $agendaItemQuery->where('start_date', '<=', $endDate);
        }

        $agendaItemQuery->oldest('start_date');

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
