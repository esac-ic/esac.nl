<?php

use App\Models\Event;
use App\Models\NewsItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

/**
 * Moves the event images and the news headers into the media library, which makes the cropped versions from them.
 * A news item's separate thumbnail file goes, as the library crops it from the header. Missing files are skipped.
 */
return new class extends Migration
{
    public function up(): void
    {
        $disk = Storage::disk('public');

        foreach (DB::table('events')->where('image_path', '!=', '')->get(['id', 'image_path']) as $event) {
            if ($disk->exists($event->image_path)) {
                Event::query()->findOrFail($event->id)->addMediaFromDisk($event->image_path, 'public')->toMediaCollection('image');
            }
        }

        foreach (DB::table('news_items')->where('image_path', '!=', '')->get(['id', 'image_path', 'thumbnail_path']) as $newsItem) {
            if ($disk->exists($newsItem->image_path)) {
                NewsItem::withTrashed()->findOrFail($newsItem->id)->addMediaFromDisk($newsItem->image_path, 'public')->toMediaCollection('image');
            }
            $disk->delete($newsItem->thumbnail_path);
        }

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });
        Schema::table('news_items', function (Blueprint $table) {
            $table->dropColumn(['image_path', 'thumbnail_path']);
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('image_path')->default('');
        });
        Schema::table('news_items', function (Blueprint $table) {
            $table->string('image_path')->default('');
            $table->string('thumbnail_path')->default('');
        });
    }
};
