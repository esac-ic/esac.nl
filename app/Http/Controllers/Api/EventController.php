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
            'start' => ['nullable', 'integer', 'min:0'],
            'category' => ['nullable', 'integer'],
        ]);

        $events = Event::query()
            ->with([
                'registrationForm',
                // Only the current user's registration, to show whether they registered.
                'registrations' => fn ($query) => $query->where('user_id', $request->user()?->id),
            ])
            ->withCount('registrations')
            ->when($request->integer('category'), fn ($query, $category) => $query->where('event_category_id', $category))
            ->when($request->date('startDate', 'd-m-Y', config('app.display_timezone'))?->startOfDay()->utc(), fn ($query, $startDate) => $query->where('end_date', '>=', $startDate))
            ->oldest('start_date');

        // The agenda's JavaScript reads these keys and shows nine events a page.
        return response()->json([
            'agendaItemCount' => $events->count(),
            'agendaItems' => EventResource::collection(
                $events->skip($request->integer('start'))->take(9)->get()
            ),
        ]);
    }
}
