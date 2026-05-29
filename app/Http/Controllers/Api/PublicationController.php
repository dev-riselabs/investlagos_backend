<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Publication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicationController extends Controller
{
    /**
     * Public listing with optional search and filters.
     *
     * Query params:
     *  - q         : free-text search (title + description)
     *  - category  : exact category match
     *  - year      : exact year match
     *  - per_page  : page size (default 12, max 50)
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 12);
        $perPage = max(1, min($perPage, 50));

        $publications = Publication::query()
            ->published()
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%' . $request->string('q') . '%';
                $q->where(function ($inner) use ($term) {
                    $inner->where('title', 'like', $term)
                        ->orWhere('description', 'like', $term);
                });
            })
            ->when($request->filled('category'), fn ($q) => $q->where('category', $request->string('category')))
            ->when($request->filled('year'), fn ($q) => $q->where('year', (int) $request->input('year')))
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate($perPage);

        return response()->json($publications);
    }

    public function show(Publication $publication): JsonResponse
    {
        abort_unless($publication->is_published, 404);

        return response()->json(['data' => $publication]);
    }

    /**
     * Surface the distinct categories and years that drive the filter bar
     * on the publications page.
     */
    public function filters(): JsonResponse
    {
        return response()->json([
            'categories' => Publication::query()
                ->published()
                ->whereNotNull('category')
                ->distinct()
                ->orderBy('category')
                ->pluck('category'),
            'years' => Publication::query()
                ->published()
                ->distinct()
                ->orderByDesc('year')
                ->pluck('year'),
        ]);
    }
}
