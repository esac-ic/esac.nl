<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PennoMemberExport;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * The members who joined in the last days, for the financial administration.
 */
class PennoExportController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:manage-members'),
        ];
    }

    public function create(): View
    {
        return view('admin.penno-export.create');
    }

    public function store(Request $request): StreamedResponse
    {
        $request->validate([
            'days_ago' => ['required', 'integer', 'min:0'],
        ]);
        $export = new PennoMemberExport($request->integer('days_ago'));
        $exportDate = $export->since()->toDateString();

        return $export->download("Members (created after $exportDate).xlsx");
    }
}
