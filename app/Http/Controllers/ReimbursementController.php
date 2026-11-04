<?php

namespace App\Http\Controllers;

use App\Models\Reimbursement;
use App\Repositories\ReimbursementCategoryRepository;
use App\Repositories\ReimbursementRepository;
use App\Repositories\ApplicationFormRepositories\ApplicationFormRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Intervention\Image\ImageManagerStatic as Image;

class ReimbursementController extends Controller
{
    private ReimbursementRepository $_reimbursementRepository;

    public function __construct(ReimbursementRepository $reimbursementRepository) {
        $this->middleware('auth');

        $this->_reimbursementRepository = $reimbursementRepository;
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
        Session::flash("message", 'Event edited');
        return redirect('/reimbursement');
    }

    public function destroy($id)
    {
        $this->_reimbursementRepository->delete($id);
        Session::flash("message", 'Event removed');
        return redirect('/reimbursement');
    }

    public function copy(Reimbursement $reimbursement)
    {
        $newReimbursement = $this->_reimbursementRepository->copy($reimbursement);
        return redirect('/reimbursement/' . $newReimbursement->id . '/edit');
    }

    private function validateInput(Request $request)
    {
        $this->validate($request, [
            
        ]);
    }

    private function renderCreateEditView($reimbursement, $fields)
    {
        return view('beheer.reimbursement.create_edit', compact(['fields', 'reimbursement']));
    }

    private function handleImage(Request $request, Reimbursement $reimbursement)
    {
        if ($request->hasFile('thumbnail')) {
            $name = $reimbursement->id . '-thumbnail.' . $request->thumbnail->extension();
            $request->file('thumbnail')->storeAs('reimbursement', $name, 'public');
            $reimbursement->image_url = 'reimbursement/' . $name;
            $reimbursement->save();
            $img_path = "../storage/app/public/" . $reimbursement->image_url;
            Image::make($img_path)->fit(400, 300)->save($img_path);
        }
    }

    private function getFields($method, $url, $title)
    {
        return [
            'title_info' => 'Event general information',
            'title_content' => 'Event item content',
            'title_image' => 'Event item thumbnail',
            'title' => $title,
            'method' => $method,
            'url' => $url,
        ];
    }
}
