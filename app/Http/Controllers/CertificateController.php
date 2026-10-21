<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\Certificate;
use App\Repositories\CertificateRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CertificateController extends Controller
{
    public function __construct(private readonly CertificateRepository $_certificateRepository)
    {
        $this->middleware('auth');
        $this->middleware('authorize:' . Role::Administrator->value . ',' . Role::CertificateAdministrator->value);
    }

    public function index(): View
    {
        $certificates = $this->_certificateRepository->all(["id", "name"]);
        return view("beheer.certificate.index", compact("certificates"));
    }

    public function create(): View
    {
        $fields = ['title' => 'Add a new certificate',
            'method' => 'POST',
            'url' => '/certificates'];
        $certificate = null;
        return view("beheer.certificate.create_edit", compact('certificate', 'fields'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->validateData($request);
        $this->_certificateRepository->create($request->all());

        Session::flash("message", 'Certificate added');
        return redirect('/certificates');
    }

    public function show(Certificate $certificate): View
    {
        return view('beheer.certificate.show', compact('certificate'));
    }

    public function edit(Certificate $certificate): View
    {
        $fields = ['title' => 'Add a new certificate',
            'method' => 'PATCH',
            'url' => '/certificates/' . $certificate->id];

        return view("beheer.certificate.create_edit", compact('certificate', 'fields'));
    }

    public function update(Request $request, Certificate $certificate): RedirectResponse
    {
        $this->validateData($request);
        $this->_certificateRepository->update($certificate->id, $request->all());

        Session::flash("message", 'Certificate edited');
        return redirect('/certificates');
    }

    public function destroy(Certificate $certificate): RedirectResponse
    {
        $this->_certificateRepository->delete($certificate->id);

        Session::flash("message", 'Certificate removed');
        return redirect('/certificates');
    }

    private function validateData(Request $reqeust): void
    {
        $this->validate($reqeust, [
            'name' => 'required|max:255',
            'abbreviation' => 'required|max:255',
        ]);
    }
}
