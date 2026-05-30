<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    /**
     * Paginated, searchable index of newsletter subscribers.
     *
     * Query params:
     *  - q         : free-text search across name, email, company
     *  - country   : exact country match
     *  - industry  : exact industry match
     *  - is_active : 1|0
     *  - per_page  : default 25, max 200
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 25);
        $perPage = max(1, min($perPage, 200));

        $subscribers = Subscriber::query()
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%' . $request->string('q') . '%';
                $q->where(function ($inner) use ($term) {
                    $inner->where('first_name', 'like', $term)
                        ->orWhere('last_name', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('company_name', 'like', $term);
                });
            })
            ->when($request->filled('country'), fn ($q) => $q->where('country', $request->string('country')))
            ->when($request->filled('industry'), fn ($q) => $q->where('industry', $request->string('industry')))
            ->when($request->filled('is_active'), function ($q) use ($request) {
                $q->where('is_active', (bool) $request->boolean('is_active'));
            })
            ->latest('id')
            ->paginate($perPage);

        return response()->json($subscribers);
    }

    public function show(Subscriber $subscriber): JsonResponse
    {
        return response()->json(['data' => $subscriber]);
    }

    public function destroy(Subscriber $subscriber): JsonResponse
    {
        $subscriber->delete();

        return response()->json(['message' => 'Subscriber deleted.']);
    }

    /**
     * Lightweight stats endpoint for the admin dashboard.
     */
    public function stats(): JsonResponse
    {
        return response()->json([
            'total'      => Subscriber::count(),
            'active'     => Subscriber::where('is_active', true)->count(),
            'inactive'   => Subscriber::where('is_active', false)->count(),
            'by_country' => Subscriber::selectRaw('country, COUNT(*) as total')
                ->groupBy('country')
                ->orderByDesc('total')
                ->limit(10)
                ->get(),
        ]);
    }
}
