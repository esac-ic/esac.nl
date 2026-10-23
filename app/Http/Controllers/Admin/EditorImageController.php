<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;

/**
 * The images that the rich text editor uploads into page content.
 */
class EditorImageController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:upload-editor-images'),
        ];
    }

    public function store(Request $request): string
    {
        $request->validate(['image' => ['required', File::image()->max('10mb')]]);

        return Storage::disk('public')->url($request->file('image')->store('image_uploads', 'public'));
    }
}
