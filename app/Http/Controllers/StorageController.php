<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;

class StorageController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:manage-pages'),
        ];
    }

    public function uploadImage(Request $request): string
    {
        $request->validate(['image' => ['required', 'image', 'max:10240']]);

        return Storage::disk('public')->url($request->file('image')->store('image_uploads', 'public'));
    }
}
