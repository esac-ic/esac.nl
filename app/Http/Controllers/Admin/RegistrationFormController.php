<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationFormRequest;
use App\Http\Resources\RegistrationFormFieldResource;
use App\Models\RegistrationForm;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;

class RegistrationFormController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:manage-events'),
        ];
    }

    public function index(): View
    {
        return view('admin.registration-forms.index', [
            'registrationForms' => RegistrationForm::all(),
        ]);
    }

    public function create(): View
    {
        return $this->form(new RegistrationForm);
    }

    public function store(RegistrationFormRequest $request): RedirectResponse
    {
        DB::transaction(fn () => RegistrationForm::query()->create($request->safe()->only('name'))->syncFields($request->validated('rows', [])));

        return to_route('admin.registration-forms.index')->with('message', 'Registration form added');
    }

    public function show(RegistrationForm $registrationForm): View
    {
        return view('admin.registration-forms.show', ['registrationForm' => $registrationForm->load('fields.options')]);
    }

    public function edit(RegistrationForm $registrationForm): View
    {
        return $this->form($registrationForm);
    }

    public function update(RegistrationFormRequest $request, RegistrationForm $registrationForm): RedirectResponse
    {
        DB::transaction(function () use ($request, $registrationForm) {
            $registrationForm->update($request->safe()->only('name'));
            $registrationForm->syncFields($request->validated('rows', []));
        });

        return to_route('admin.registration-forms.index')->with('message', 'Registration form edited');
    }

    public function destroy(RegistrationForm $registrationForm): RedirectResponse
    {
        $registrationForm->fields->each->delete();
        $registrationForm->delete();

        return to_route('admin.registration-forms.index')->with('message', 'Registration form removed');
    }

    private function form(RegistrationForm $registrationForm): View
    {
        return view('admin.registration-forms.form', [
            'registrationForm' => $registrationForm->load('fields.options'),
            'fields' => RegistrationFormFieldResource::collection($registrationForm->fields),
        ]);
    }
}
