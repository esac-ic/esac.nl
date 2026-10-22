<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'startDate' => ['nullable', 'date_format:d-m-Y'],
            'endDate' => ['nullable', 'date_format:d-m-Y'],
            'start' => ['nullable', 'integer', 'min:0'],
            // The admin events table loads every event at once.
            'limit' => ['nullable', 'integer', 'min:1'],
            'category' => ['nullable', 'integer'],
        ]);

        $events = Event::query()
            ->with([
                'category',
                'registrationForm',
                // Only the current user's registration, to show whether they registered.
                'registrations' => fn ($query) => $query->where('user_id', $request->user()?->id),
            ])
            ->withCount('registrations')
            ->when($request->integer('category'), fn ($query, $category) => $query->where('event_category_id', $category))
            ->when($request->date('startDate', 'd-m-Y', config('app.display_timezone'))?->startOfDay()->utc(), fn ($query, $startDate) => $query->where(
                fn ($query) => $query->where('start_date', '>=', $startDate)->orWhere('end_date', '>=', $startDate)
            ))
            ->when($request->date('endDate', 'd-m-Y', config('app.display_timezone'))?->endOfDay()->utc(), fn ($query, $endDate) => $query->where('start_date', '<=', $endDate))
            ->oldest('start_date');

        // The JavaScript reads these keys.
        return response()->json([
            'agendaItemCount' => $events->count(),
            'agendaItems' => EventResource::collection(
                $events->skip($request->integer('start'))->take($request->integer('limit', 9))->get()
            ),
        ]);
    }
}
