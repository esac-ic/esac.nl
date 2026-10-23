<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'pendingMembers' => User::query()->pending()->count(),
            'activeMembers' => User::query()->active()->count(),
            'upcomingEvents' => Event::query()->upcoming()->count(),
        ]);
    }
}
