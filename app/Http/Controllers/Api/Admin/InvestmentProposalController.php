<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvestmentProposal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InvestmentProposalController extends Controller
{
    private const STATUSES = ['pending', 'under_review', 'approved', 'rejected'];

    /**
     * Paginated, searchable index of submitted investment proposals.
     *
     * Query params:
     *  - q       : free-text search across project title, project owner, email, organization
     *  - sector  : exact sector match
     *  - status  : pending|under_review|approved|rejected
     *  - per_page: default 25, max 200
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 25);
        $perPage = max(1, min($perPage, 200));

        $proposals = InvestmentProposal::query()
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%' . $request->string('q') . '%';
                $q->where(function ($inner) use ($term) {
                    $inner->where('project_title', 'like', $term)
                        ->orWhere('project_owner', 'like', $term)
                        ->orWhere('project_owner_email', 'like', $term)
                        ->orWhere('organization', 'like', $term);
                });
            })
            ->when($request->filled('sector'), fn ($q) => $q->where('sector', $request->string('sector')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->latest('id')
            ->paginate($perPage);

        return response()->json($proposals);
    }

    public function show(InvestmentProposal $investmentProposal): JsonResponse
    {
        return response()->json(['data' => $investmentProposal]);
    }

    public function update(Request $request, InvestmentProposal $investmentProposal): JsonResponse
    {
        $data = $request->validate([
            'status'      => ['sometimes', 'string', Rule::in(self::STATUSES)],
            'admin_notes' => ['nullable', 'string', 'max:4000'],
        ]);

        if (array_key_exists('status', $data) && $data['status'] !== 'pending') {
            $data['reviewed_at'] = now();
        }

        $investmentProposal->update($data);

        return response()->json([
            'message' => 'Investment proposal updated.',
            'data'    => $investmentProposal->fresh(),
        ]);
    }

    public function destroy(InvestmentProposal $investmentProposal): JsonResponse
    {
        $investmentProposal->delete();

        return response()->json(['message' => 'Investment proposal deleted.']);
    }

    /** Lightweight stats payload for the admin dashboard. */
    public function stats(): JsonResponse
    {
        return response()->json([
            'total'        => InvestmentProposal::count(),
            'pending'      => InvestmentProposal::where('status', 'pending')->count(),
            'under_review' => InvestmentProposal::where('status', 'under_review')->count(),
            'approved'     => InvestmentProposal::where('status', 'approved')->count(),
            'rejected'     => InvestmentProposal::where('status', 'rejected')->count(),
            'by_sector'    => InvestmentProposal::selectRaw('sector, COUNT(*) as total')
                ->groupBy('sector')
                ->orderByDesc('total')
                ->get(),
        ]);
    }

}
