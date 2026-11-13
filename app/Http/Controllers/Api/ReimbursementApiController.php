<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Reimbursement;
use App\Models\ReimbursementItem; 
use App\Models\User; 
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReimbursementApiController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getReimbursements(Request $request)
    {
        $reimbursementQuery = Reimbursement::where('createdBy', $request->user()->id);
        $reimbursementQuery->orderBy('createdOn', 'desc');

        $reimbursementCount = $reimbursementQuery->count();

        $limit = $request->input('limit', 9);
        $reimbursements = $reimbursementQuery
            ->take($limit)
            ->get()
            ->map(function ($reimbursement) {
                return [
                    'id' => $reimbursement->id,
                    'title' => $reimbursement->title,
                    'createdOn' => $reimbursement->createdOn,
                    'text' => $reimbursement->text,
                    'status' => $reimbursement->status,
                    'amount' => "€ " . number_format($reimbursement->getAmount(), 2),
                    'type' => $reimbursement->type
                ];
            });

        return response()->json([
            'reimbursementCount' => $reimbursementCount,
            'reimbursements' => $reimbursements,
        ]);
    }

    public function getReimbursementItems(Request $request)
    {
        $reimbursement_id = $request->get('reimbursement', -1);
        $reimbursement = Reimbursement::find($reimbursement_id);

        if (Auth::user()->id != $reimbursement->createdBy) {
            abort(401);
        }
    
        $reimbursementItemQuery = $reimbursement->getReimbursementItems();
        
        $reimbursementItemCount = $reimbursementItemQuery->count();

        $reimbursementItems = $reimbursementItemQuery
            ->get()
            ->map(function ($reimbursementItem) {
                return [
                    'id' => $reimbursementItem->id,
                    'item' => $reimbursementItem->item,
                    'amount' => $reimbursementItem->amount,
                    'esacpas' => $reimbursementItem->esacpas,
                    'paidBy' => User::find($reimbursementItem->paidBy)?->getName(),
                    'image_url' => $reimbursementItem->image_url,
                ];
            });

        return response()->json([
            'reimbursementItemCount' => $reimbursementItemCount,
            'reimbursementItems' => $reimbursementItems,
        ]);
    }
}
