<?php

namespace App\Http\Controllers;

use App\Http\Requests\CertificateRequest;
use App\Models\Certificate;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

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
        return view('beheer.certificate.index', [
            'certificates' => Certificate::all(['id', 'name']),
        ]);
    }

    public function create(): View
    {
        return view('beheer.certificate.create_edit', ['certificate' => new Certificate]);
    }

    public function store(CertificateRequest $request): RedirectResponse
    {
        Certificate::create($request->validated());

        return to_route('certificates.index')->with('message', 'Certificate added');
    }

    public function show(Certificate $certificate): View
    {
        return view('beheer.certificate.show', ['certificate' => $certificate]);
    }

    public function edit(Certificate $certificate): View
    {
        return view('beheer.certificate.create_edit', ['certificate' => $certificate]);
    }

    public function update(CertificateRequest $request, Certificate $certificate): RedirectResponse
    {
        $certificate->update($request->validated());

        return to_route('certificates.index')->with('message', 'Certificate edited');
    }

    public function destroy(Certificate $certificate): RedirectResponse
    {
        $certificate->delete();

        return to_route('certificates.index')->with('message', 'Certificate removed');
    }
}
