<?php

namespace App\Http\Controllers;

use App\Models\Reimbursement\Reimbursement;
use App\Models\Reimbursement\ReimbursementReceipt;
use App\Enums\ReimbursementStatus;
use App\Repositories\ReimbursementRepositories\ReimbursementRepository;
use App\Repositories\ReimbursementRepositories\ReimbursementReceiptRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class ReimbursementController extends Controller
{
    private ReimbursementRepository $_reimbursementRepository;
    private ReimbursementReceiptRepository $_reimbursementReceiptRepository;

    public function __construct(ReimbursementRepository $reimbursementRepository, ReimbursementReceiptRepository $reimbursementReceiptRepository) {
        $this->middleware('auth');

        $this->_reimbursementRepository = $reimbursementRepository;
        $this->_reimbursementReceiptRepository = $reimbursementReceiptRepository;
    }

    public function index()
    {
        return view('beheer.reimbursement.index');
    }

    public function create()
    {
        $fields = $this->getFields('POST', '/reimbursement', 'Add a reimbursement');
        return $this->renderCreateEditView(null, $fields);
    }

    public function store(Request $request)
    {
        return redirect('/reimbursement');
    }

    public function show(Reimbursement $reimbursement)
    {
        return view('beheer.reimbursement.show', compact('reimbursement'));
    }

    public function edit(Reimbursement $reimbursement)
    {
        $fields = $this->getFields('PATCH', '//' . $reimbursement->id, 'Edit event');
        return $this->renderCreateEditView($reimbursement, $fields);
    }

    public function update(Request $request, Reimbursement $reimbursement)
    {
        $this->validateInput($request);
        $this->_reimbursementRepository->update($reimbursement->id, $request->all());
        $this->handleImage($request, $reimbursement);
        Session::flash("message", 'Reimbursement edited');
        return redirect('/reimbursement');
    }

    public function destroy(Reimbursement $reimbursement)
    {
        if (Auth::user()->hasRole(Config::get('constants.Administrator')) 
            || $reimbursement->status == ReimbursementStatus::DRAFT
            || $reimbursement->status == ReimbursementStatus::REJECTED
        ) {
            $this->_reimbursementRepository->delete($reimbursement->id);
            Session::flash("message", 'Reimbursement removed');
        }
        return redirect('/reimbursement');
    }
    
    public function destroyReceipt(ReimbursementReceipt $receipt)
    {
        \Log::info($receipt);
        $reimbursement = $receipt->getReimbursement;
        if (Auth::user()->hasRole(Config::get('constants.Administrator')) 
            || $reimbursement->status == ReimbursementStatus::DRAFT
            || $reimbursement->status == ReimbursementStatus::REJECTED
        ) {
            $this->_reimbursementReceiptRepository->delete($receipt->id);
            Session::flash("message", 'Receipt removed');
        }
        return redirect('/reimbursement/' . $reimbursement->id);
    }

    private function validateInput(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'text' => 'required',
            'status' => [
                'required|max:255',
                Rule::enum(ReimbursementStatus::class)
            ],
            'type' => [
                'required|max:255',
                Rule::enum(ReimbursementType::class)
            ],
            'createdOn' => 'required|max:255'
        ]);
    }

    private function renderCreateEditView($reimbursement, $fields)
    {
        return view('beheer.reimbursement.create_edit', compact(['fields', 'reimbursement']));
    }

    private function handleImage(Request $request, ReimbursementReceipt $receipt)
    {
        if ($request->hasFile('image')) {
            $name = $receipt->reimbursement_id . '-receipt-' . $receipt->id . '.' . $request->image->extension();
            $request->file('image')->storeAs('reimbursement_receipt', $name, 'public');
            $receipt->image_url = 'reimbursement_receipt/' . $name;
            $receipt->save();
            $img_path = "../storage/app/public/" . $receipt->image_url;
            Image::make($img_path)->contain(600, 600)->save($img_path);
        }
    }
}
