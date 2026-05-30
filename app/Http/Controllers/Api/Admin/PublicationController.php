<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePublicationRequest;
use App\Http\Requests\Admin\UpdatePublicationRequest;
use App\Mail\NewPublicationNotificationMail;
use App\Models\Publication;
use App\Models\Subscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Throwable;

class PublicationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);
        $perPage = max(1, min($perPage, 100));

        $publications = Publication::query()
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%' . $request->string('q') . '%';
                $q->where(function ($inner) use ($term) {
                    $inner->where('title', 'like', $term)
                        ->orWhere('description', 'like', $term)
                        ->orWhere('category', 'like', $term);
                });
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $status = $request->string('status');
                if ($status === 'published') $q->where('is_published', true);
                if ($status === 'draft') $q->where('is_published', false);
            })
            ->latest('id')
            ->paginate($perPage);

        return response()->json($publications);
    }

    public function show(Publication $publication): JsonResponse
    {
        return response()->json(['data' => $publication]);
    }

    public function store(StorePublicationRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('publications/images', 'public');
        }
        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('publications/files', 'public');
        }

        $data['author_id'] = $request->user()->id;
        $data['is_published'] = $data['is_published'] ?? true;
        if (! empty($data['is_published']) && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        unset($data['image'], $data['file']);

        $publication = Publication::create($data);

        if ($publication->is_published) {
            $this->notifySubscribers($publication);
        }

        return response()->json([
            'message' => 'Publication created.',
            'data'    => $publication->fresh(),
        ], 201);
    }

    public function update(UpdatePublicationRequest $request, Publication $publication): JsonResponse
    {
        $wasPublished = (bool) $publication->is_published;
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $this->deleteIfExists($publication->image_path);
            $data['image_path'] = $request->file('image')->store('publications/images', 'public');
        } elseif (! empty($data['remove_image'])) {
            $this->deleteIfExists($publication->image_path);
            $data['image_path'] = null;
        }

        if ($request->hasFile('file')) {
            $this->deleteIfExists($publication->file_path);
            $data['file_path'] = $request->file('file')->store('publications/files', 'public');
        } elseif (! empty($data['remove_file'])) {
            $this->deleteIfExists($publication->file_path);
            $data['file_path'] = null;
        }

        // Auto-set published_at the first time something is published.
        if (
            array_key_exists('is_published', $data)
            && $data['is_published']
            && empty($publication->published_at)
            && empty($data['published_at'])
        ) {
            $data['published_at'] = now();
        }

        unset($data['image'], $data['file'], $data['remove_image'], $data['remove_file']);

        $publication->update($data);

        // Notify subscribers the first time a publication flips to published.
        if (! $wasPublished && $publication->fresh()->is_published) {
            $this->notifySubscribers($publication->fresh());
        }

        return response()->json([
            'message' => 'Publication updated.',
            'data'    => $publication->fresh(),
        ]);
    }

    public function destroy(Publication $publication): JsonResponse
    {
        $this->deleteIfExists($publication->image_path);
        $this->deleteIfExists($publication->file_path);
        $publication->delete();

        return response()->json(['message' => 'Publication deleted.']);
    }

    private function deleteIfExists(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Email every active subscriber about a newly published publication.
     * Each send is wrapped so one failure cannot break the others or the request.
     */
    private function notifySubscribers(Publication $publication): void
    {
        Subscriber::query()
            ->where('is_active', true)
            ->select(['id', 'first_name', 'last_name', 'email'])
            ->chunkById(200, function ($subscribers) use ($publication) {
                foreach ($subscribers as $subscriber) {
                    try {
                        Mail::to($subscriber->email)
                            ->send(new NewPublicationNotificationMail($subscriber, $publication));
                    } catch (Throwable $e) {
                        Log::error('Publication notification email failed', [
                            'subscriber_id'  => $subscriber->id,
                            'publication_id' => $publication->id,
                            'exception'      => $e->getMessage(),
                        ]);
                    }
                }
            });
    }
}
