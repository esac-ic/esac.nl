<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class StorageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('authorize:' . Config::get('constants.Content_administrator'));
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
