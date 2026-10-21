<?php

namespace App\Http\Controllers;

use App\Enums\Role;
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
            new Middleware('authorize:' . Role::ContentAdministrator->value),
        ];
    }

    public function uploadImage(Request $request): string
    {
        $path = $request->file('image')->store('image_uploads', 'public');

        return '/storage/' . $path;
    }

    public function deleteImage(Request $request): void
    {
        Storage::disk('public')->delete($request->path);
    }
}
