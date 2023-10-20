<?php

namespace App\Http\Controllers\Api;

use App\AgendaItem;
use App\AgendaItemCategory;
use App\Http\Controllers\Controller;
use App\Services\AgendaApplicationFormService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    protected $agendaApplicationFormService;

    public function __construct(AgendaApplicationFormService $agendaApplicationFormService)
    {
        $this->agendaApplicationFormService = $agendaApplicationFormService;
    }

    public function getAgenda(Request $request)
    {
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
                $query->where('startDate', '>=', $startDate)
                    ->orWhere('endDate', '>=', $startDate);
            });
        }

        if ($endDate = $request->input('endDate')) {
            $endDate = Carbon::createFromFormat('d-m-Y', $endDate)->endOfDay();
            $agendaItemQuery->where('startDate', '<=', $endDate);
        }

        $agendaItemQuery->orderBy('startDate', 'asc');

        $agendaItemCount = $agendaItemQuery->count();

        $agendaItems = $agendaItemQuery
            ->skip($start)
            ->take($limit)
            ->get()
            ->map(function ($agendaItem) use ($request) {
                $registeredUserIds = $agendaItem->application_form_id
                ? $this->agendaApplicationFormService->getRegisteredUserIds($agendaItem)
                : [];

                $currentUser = $request->user();
                $currentUserSignedUp = $currentUser ? in_array($currentUser->id, $registeredUserIds) : false;

                return [
                    'id' => $agendaItem->id,
                    'title' => $agendaItem->title,
                    'thumbnail' => $agendaItem->getImageUrl(),
                    'startDate' => Carbon::parse($agendaItem->startDate)->format('d M'),
                    'endDate' => $agendaItem->endDate,
                    'full_startDate' => $agendaItem->startDate,
                    'category' => $agendaItem->agendaItemCategory->name,
                    'text' => $agendaItem->shortDescription,
                    'formId' => $agendaItem->getApplicationForm,
                    'canRegister' => $agendaItem->canRegister(),
                    'application_form_id' => $agendaItem->application_form_id,
                    'amountOfPeopleRegisterd' => count($registeredUserIds),
                    'currentUserSignedUp' => $currentUserSignedUp,
                ];
            });

        return response()->json([
            'agendaItemCount' => $agendaItemCount,
            'agendaItems' => $agendaItems,
        ]);
    }

    public function getCategories()
    {
        $categories = AgendaItemCategory::all()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                ];
            });

        return response()->json($categories);
    }
}
