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
            'from' => ['nullable', 'date_format:Y-m-d'],
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
            ->when($request->date('from', 'Y-m-d', config('app.display_timezone'))?->startOfDay()->utc(), fn ($query, $from) => $query->where('end_date', '>=', $from))
            ->oldest('start_date');

        return EventResource::collection($events->paginate(9))->response();
    }
}
