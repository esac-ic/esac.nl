<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Session;

class CertificateController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:manage-certificates'),
        ];
    }

    public function index(): View
    {
        $certificates = Certificate::all(["id", "name"]);
        return view("beheer.certificate.index", compact("certificates"));
    }

    public function create(): View
    {
        $fields = ['title' => 'Add a new certificate',
            'method' => 'POST',
            'url' => route('certificates.store')];
        $certificate = null;
        return view("beheer.certificate.create_edit", compact('certificate', 'fields'));
    }

    public function store(Request $request): RedirectResponse
    {
        Certificate::create($this->validateInput($request));

        Session::flash("message", 'Certificate added');
        return to_route('certificates.index');
    }

    public function show(Certificate $certificate): View
    {
        return view('beheer.certificate.show', compact('certificate'));
    }

    public function edit(Certificate $certificate): View
    {
        $fields = ['title' => 'Add a new certificate',
            'method' => 'PATCH',
            'url' => route('certificates.update', $certificate)];

        return view("beheer.certificate.create_edit", compact('certificate', 'fields'));
    }

    public function update(Request $request, Certificate $certificate): RedirectResponse
    {
        $certificate->update($this->validateInput($request));

        Session::flash("message", 'Certificate edited');
        return to_route('certificates.index');
    }

    public function destroy(Certificate $certificate): RedirectResponse
    {
        $certificate->delete();

        Session::flash("message", 'Certificate removed');
        return to_route('certificates.index');
    }

    private function validateInput(Request $request): array
    {
        return $request->validate([
            'name' => 'required|max:255',
            'abbreviation' => 'required|max:255',
        ]);
    }
}
