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
            ->withCount('getApplicationFormResponses')
            ->when($request->integer('category'), fn ($query, $category) => $query->where('category', $category))
            ->when($request->date('startDate', 'd-m-Y', config('app.display_timezone'))?->startOfDay()->utc(), fn ($query, $startDate) => $query->where(
                fn ($query) => $query->where('start_date', '>=', $startDate)->orWhere('end_date', '>=', $startDate)
            ))
            ->when($request->date('endDate', 'd-m-Y', config('app.display_timezone'))?->endOfDay()->utc(), fn ($query, $endDate) => $query->where('start_date', '<=', $endDate))
            ->oldest('start_date');

        return response()->json([
            'agendaItemCount' => $agendaItemQuery->count(),
            'agendaItems' => AgendaItemResource::collection(
                $agendaItemQuery->skip($request->integer('start'))->take($request->integer('limit', 9))->get()
            ),
        ]);
    }

    public function getCategories(): JsonResponse
    {
        return response()->json(AgendaItemCategoryResource::collection(AgendaItemCategory::all()));
    }
}
