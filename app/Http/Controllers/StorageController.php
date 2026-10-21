<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StorageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('authorize:' . Role::ContentAdministrator->value);
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
