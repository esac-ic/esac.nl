<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactDetailsRequest;
use App\Settings\ContactSettings;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ContactDetailsController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:manage-pages'),
        ];
    }

    public function edit(ContactSettings $contact): View
    {
        return view('admin.contact-details.edit', ['contact' => $contact]);
    }

    public function update(ContactDetailsRequest $request, ContactSettings $contact): RedirectResponse
    {
        $contact->fill($request->validated())->save();

        return to_route('admin.contact-details.edit')->with('message', 'Contact details updated');
    }
}
