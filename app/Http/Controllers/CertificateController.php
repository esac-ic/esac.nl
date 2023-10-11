<?php

namespace App\Http\Controllers;

use App\Certificate;
use App\Repositories\CertificateRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class CertificateController extends Controller
{
    private $_certificateRepository;

    public function __construct(CertificateRepository $certificateRepository)
    {
        $this->middleware('auth');
        $this->middleware('authorize:' . Config::get('constants.Administrator') . ',' . Config::get('constants.Certificate_administrator'));

        $this->_certificateRepository = $certificateRepository;
    }

    public function index()
    {
        $certificates = $this->_certificateRepository->all(array("id", "name"));
        return view("beheer.certificate.index", compact("certificates"));
    }

    public function create()
    {
        $fields = ['title' => 'Add a new certificate',
            'method' => 'POST',
            'url' => '/certificates'];
        $certificate = null;
        return view("beheer.certificate.create_edit", compact('certificate', 'fields'));
    }

    public function store(Request $request)
    {
        $this->validateData($request);
        $this->_certificateRepository->create($request->all());

        Session::flash("message", 'Certificate added');
        return redirect('/certificates');
    }

    public function show(Certificate $certificate)
    {
        return view('beheer.certificate.show', compact('certificate'));
    }

    public function edit(Certificate $certificate)
    {
        $fields = ['title' => 'Add a new certificate',
            'method' => 'PATCH',
            'url' => '/certificates/' . $certificate->id];

        return view("beheer.certificate.create_edit", compact('certificate', 'fields'));
    }

    public function update(Request $request, Certificate $certificate)
    {
        $this->validateData($request);
        $this->_certificateRepository->update($certificate->id, $request->all());

        Session::flash("message", 'Certificate edited');
        return redirect('/certificates');
    }

    public function destroy(Certificate $certificate)
    {
        $this->_certificateRepository->delete($certificate->id);

        Session::flash("message", 'Certificate removed');
        return redirect('/certificates');
    }

    private function validateData(Request $reqeust)
    {
        $this->validate($reqeust, [
            'name' => 'required|max:255',
            'abbreviation' => 'required|max:255',
        ]);
    }
}
