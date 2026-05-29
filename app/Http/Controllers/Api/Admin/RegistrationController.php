<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateRegistrationRequest;
use App\Models\Registration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    /**
     * Paginated, searchable index of summit registrations for the admin.
     *
     * Query params:
     *  - q              : free-text search across name, email, organization
     *  - sector         : exact sector match
     *  - attending_as   : exact role match
     *  - deal_room      : Yes|No
     *  - attendance_mode: Attend In-Person|Attend Virtually
     *  - per_page       : default 25, max 200
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 25);
        $perPage = max(1, min($perPage, 200));

        $registrations = Registration::query()
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%' . $request->string('q') . '%';
                $q->where(function ($inner) use ($term) {
                    $inner->where('first_name', 'like', $term)
                        ->orWhere('last_name', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('organization', 'like', $term);
                });
            })
            ->when($request->filled('sector'), fn ($q) => $q->where('sector', $request->string('sector')))
            ->when($request->filled('attending_as'), fn ($q) => $q->where('attending_as', $request->string('attending_as')))
            ->when($request->filled('deal_room'), fn ($q) => $q->where('deal_room', $request->string('deal_room')))
            ->when($request->filled('attendance_mode'), fn ($q) => $q->where('attendance_mode', $request->string('attendance_mode')))
            ->latest('id')
            ->paginate($perPage);

        return response()->json($registrations);
    }

    public function show(Registration $registration): JsonResponse
    {
        return response()->json(['data' => $registration]);
    }

    public function update(UpdateRegistrationRequest $request, Registration $registration): JsonResponse
    {
        $data = $request->validated();

        // Mirror the public-form rule: clear deal_room_role if the
        // registrant opted out of the deal room.
        if (($data['deal_room'] ?? $registration->deal_room) === 'No') {
            $data['deal_room_role'] = null;
        }

        $registration->update($data);

        return response()->json([
            'message' => 'Registration updated.',
            'data'    => $registration->fresh(),
        ]);
    }

    public function destroy(Registration $registration): JsonResponse
    {
        $registration->delete();

        return response()->json(['message' => 'Registration deleted.']);
    }

    /**
     * Lightweight stats endpoint for the admin dashboard.
     */
    public function stats(): JsonResponse
    {
        return response()->json([
            'total'        => Registration::count(),
            'in_person'    => Registration::where('attendance_mode', 'Attend In-Person')->count(),
            'virtual'      => Registration::where('attendance_mode', 'Attend Virtually')->count(),
            'deal_room'    => Registration::where('deal_room', 'Yes')->count(),
            'by_sector'    => Registration::selectRaw('sector, COUNT(*) as total')
                ->groupBy('sector')
                ->orderByDesc('total')
                ->get(),
        ]);
    }
}
