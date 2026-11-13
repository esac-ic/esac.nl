<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reimbursement;
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
                    'amount' => $reimbursement->getAmount(),
                    'type' => $reimbursement->type
                ];
            });

        return response()->json([
            'reimbursementCount' => $reimbursementCount,
            'reimbursements' => $reimbursements,
        ]);
    }
}
