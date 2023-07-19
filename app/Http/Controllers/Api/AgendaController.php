<?php

namespace App\Http\Controllers\Api;

use App\AgendaItem;
use App\AgendaItemCategorie;
use App\Http\Controllers\Controller;
use App\Services\AgendaApplicationFormService;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class AgendaController extends Controller
{
    public function getAgenda(Request $request)
    {
        $agendaItemQuery = AgendaItem::query()
            ->with(
                'agendaItemShortDescription',
                'getApplicationForm',
                'getApplicationFormResponses',
                'agendaItemCategory',
                'agendaItemCategory.categorieName'
            );
    
        // Set limit and page
        $limit = $request->get('limit', 9);
        $page = $request->get('page', 1);
        $skip = ($page - 1) * $limit;
    
        // Add parameters if they are set in the url
        if ($request->has('category')) {
            $agendaItemQuery->where('category', '=', intval($request->get('category')));
        }
        if ($request->has('startDate')) {
            $startDate = Carbon::createFromFormat('d-m-Y', $request->get('startDate'))->setTime(0, 0, 0);
            $agendaItemQuery->where(function ($query) use ($startDate) {
                $query->where("startDate", '>=', $startDate)
                    ->orWhere("endDate", '>=', $startDate);
            });
        }
        if ($request->has('endDate')) {
            $endDate = Carbon::createFromFormat('d-m-Y', $request->get('endDate'))->setTime(0, 0, 0);
            $agendaItemQuery->where("startDate", '<=', $endDate);
        }
    
        $agendaItemQuery->orderBy('startDate', 'asc');
        $agendaItemsCompleet = $agendaItemQuery->skip($skip)->take($limit)->get();
    
        $agendaItems = array();
    
        $agendaApplicationFormService = new AgendaApplicationFormService();
    
        foreach ($agendaItemsCompleet as $agendaItem) {
            $currentUserSignedUp = false;
            if ($agendaItem->application_form_id != null) {
                if ($agendaItem->canRegister()) {
                    $registeredUserIds = $agendaApplicationFormService->getRegisteredUserIds($agendaItem);
                    $currentUserSignedUp = in_array(Auth::id(), $registeredUserIds);
                }
            }
    
            array_push($agendaItems, [
                "id" => $agendaItem->id,
                "title" => $agendaItem->agendaItemTitle->text(),
                "thumbnail" => $agendaItem->getImageUrl(),
                "startDate" => Carbon::parse($agendaItem->startDate)->format('d M'),
                "endDate" => $agendaItem->endDate,
                "full_startDate" => $agendaItem->startDate,
                "category" => $agendaItem->agendaItemCategory->categorieName->text(),
                "text" => $agendaItem->agendaItemShortDescription->text(),
                "formId" => $agendaItem->getApplicationForm,
                "canRegister" => $agendaItem->canRegister(),
                "application_form_id" => $agendaItem->application_form_id,
                "amountOfPeopleRegisterd" => count($agendaItem->getApplicationFormResponses),
                "currentUserSignedUp" => $currentUserSignedUp
            ]);
        }
        return [
            "agendaItemCount" => count($agendaItemsCompleet),
            "agendaItems" => $agendaItems
        ];
    }
    

    public function getCategories(){
        $categories = AgendaItemCategorie::with('categorieName')
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->categorieName->text()
                ];
            });
            
        return response()->json($categories);
    }
}
