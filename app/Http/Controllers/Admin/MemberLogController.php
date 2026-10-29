<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MemberLogType;
use App\Exports\MemberLogExport;
use App\Http\Controllers\Controller;
use App\Models\MemberLogEntry;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MemberLogController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:view-member-log'),
        ];
    }

    public function index(): View
    {
        return view('admin.member-log.index', [
            'entries' => MemberLogEntry::all(),
            'types' => MemberLogType::cases(),
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $request->validate([
            'types' => ['nullable', 'array'],
            'types.*' => [Rule::enum(MemberLogType::class)],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        $export = new MemberLogExport(
            $request->input('types'),
            $request->date('start_date', tz: config('app.display_timezone'))->utc(),
            $request->date('end_date', tz: config('app.display_timezone'))->utc(),
        );

        return $export->download($export->fileName().'.xlsx');
    }
}
