<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventCategoryResource;
use App\Models\EventCategory;
use Illuminate\Http\JsonResponse;

class EventCategoryController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(EventCategoryResource::collection(EventCategory::all()));
    }
}
