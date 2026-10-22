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
        $path = $request->file('image')->store('image_uploads', 'public');

        return '/storage/'.$path;
    }

    public function deleteImage(Request $request): void
    {
        // The editor sends the image's src, e.g. /storage/image_uploads/x.jpg
        Storage::disk('public')->delete('image_uploads/'.basename((string) $request->input('path')));
    }
}
