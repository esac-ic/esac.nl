<?php

namespace App\Http\Controllers\Api;

use App\AgendaItem;
use App\AgendaItemCategory;
use App\Http\Controllers\Controller;
use App\Services\AgendaApplicationFormService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgendaController extends Controller
{
    public function getAgenda(Request $request)
    {
        $agendaItemQuery = AgendaItem::with([
            'getApplicationForm',
            'getApplicationFormResponses',
            'agendaItemCategory'
        ]);
    
        // Set limit and page
        $limit = $request->has('limit')? $request->get('limit'): 9;
        $start = $request->has('start')? $request->get('start') : 0;
    
        // Apply filters
        $category = $request->input('category');
        if ($category) {
            $agendaItemQuery->where('category', intval($category));
        }
    
        $startDate = $request->input('startDate');
        if ($startDate) {
            $startDate = Carbon::createFromFormat('d-m-Y', $startDate)->startOfDay();
            $agendaItemQuery->where(function ($query) use ($startDate) {
                $query->where('startDate', '>=', $startDate)
                    ->orWhere('endDate', '>=', $startDate);
            });
        }
    
        $endDate = $request->input('endDate');
        if ($endDate) {
            $endDate = Carbon::createFromFormat('d-m-Y', $endDate)->startOfDay();
            $agendaItemQuery->where('startDate', '<=', $endDate);
        }
    
        $agendaItemQuery->orderBy('startDate', 'asc');
    
        // Get total count for pagination
        $agendaItemCount = $agendaItemQuery->count();
    
        // Get paginated agenda items
        $agendaItems = $agendaItemQuery
            ->skip($start)
            ->take($limit)
            ->get()
            ->map(function ($agendaItem) {
                $agendaApplicationFormService = new AgendaApplicationFormService();
                $currentUserSignedUp = false;
    
                if ($agendaItem->application_form_id && $agendaItem->canRegister()) {
                    $registeredUserIds = $agendaApplicationFormService->getRegisteredUserIds($agendaItem);
                    $currentUserSignedUp = in_array(Auth::id(), $registeredUserIds);
                }
    
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
                    'amountOfPeopleRegisterd' => count($agendaItem->getApplicationFormResponses),
                    'currentUserSignedUp' => $currentUserSignedUp,
                ];
            });
    
        return [
            'agendaItemCount' => $agendaItemCount,
            'agendaItems' => $agendaItems,
        ];
    }
    
    public function getCategories(){
        $categories = AgendaItemCategory::all()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name
                ];
            });
            
        return response()->json($categories);
    }
}
