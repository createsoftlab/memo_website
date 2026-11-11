<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Models\Event;
use App\Models\Tribute;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EventController extends AdminEventController
{
    /**
     * Display a paginated listing of public, approved memorial events.
     */
    public function index(Request $request): View
    {
        $query = Event::with([
            'user.memories.profile.images',
            'user.profiles.images',
        ])
            ->where('status', 'approved')
            ->where('is_private', false);

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('user.profiles', function ($profileQuery) use ($search) {
                        $profileQuery->where('privacy', 'public')
                            ->where(function ($nameQuery) use ($search) {
                                $nameQuery->where('first_name', 'like', "%{$search}%")
                                    ->orWhere('last_name', 'like', "%{$search}%");
                            });
                    });
            });
        }

        if ($request->filled('event_type') && in_array($request->input('event_type'), ['upcoming', 'live', 'past'], true)) {
            $query->where('event_type', $request->input('event_type'));
        }

        $events = $query
            ->orderByDesc('start_date')
            ->latest()
            ->paginate(6)
            ->withQueryString();

        $memorialContexts = [];
        foreach ($events as $event) {
            $memorialContexts[$event->id] = $this->resolveMemorialContext($event);
        }

        return view('home.all_events', compact('events', 'memorialContexts'));
    }

    /**
     * Display the specified event details, livestream, map, and related memorial tributes.
     */
    public function show(int|string $id): View
    {
        $event = Event::with([
            'user.memories.profile.images',
            'user.profiles.images',
        ])->findOrFail($id);

        $canPreviewUnlisted = Auth::check() && (
            (int) Auth::id() === (int) $event->user_id
            || in_array(Auth::user()->role, ['super_admin', 'admin'], true)
        );

        if (!$canPreviewUnlisted && ($event->status !== 'approved' || $event->is_private)) {
            abort(404);
        }

        $memorialContext = $this->resolveMemorialContext($event);
        $memory = $memorialContext['memory'];
        $profile = $memorialContext['profile'];

        $tributes = collect();
        if ($memory) {
            $tributes = Tribute::with(['user', 'singleItem'])
                ->where('memory_id', $memory->id)
                ->where('status', 'active')
                ->latest()
                ->get();
        }

        $totalDonationAmount = (float) $tributes->sum('total_amount');
        $donorCount = $tributes->count();
        $totalFlowers = (int) $tributes->sum('flower_count');
        $totalCandles = (int) $tributes->sum('candle_count');

        $presentation = self::buildPresentation($event);
        $rawStreamUrl = $presentation['rawStreamUrl'];
        $streamEmbedUrl = $presentation['streamEmbedUrl'];
        $mapEmbedUrl = $presentation['mapEmbedUrl'];
        $eventDate = $presentation['eventDate'];
        $endDate = $presentation['endDate'];
        $countdownLabel = $presentation['countdownLabel'];
        $participationMode = $presentation['participationMode'];

        return view('home.event_detail', compact(
            'event',
            'memory',
            'profile',
            'memorialContext',
            'tributes',
            'totalDonationAmount',
            'donorCount',
            'totalFlowers',
            'totalCandles',
            'rawStreamUrl',
            'streamEmbedUrl',
            'mapEmbedUrl',
            'eventDate',
            'endDate',
            'countdownLabel',
            'participationMode'
        ));
    }

    /**
     * Resolve related memory, profile, name, and display images without extra queries.
     */
    private function resolveMemorialContext(Event $event): array
    {
        $memories = $event->user?->memories ?? collect();
        $memory = $memories->firstWhere(fn ($item) => $item->status === 'published' && (int) $item->approval === 1)
            ?? $memories->firstWhere('status', 'published')
            ?? $memories->first();

        $profile = $memory?->profile ?? $event->user?->profiles?->first();
        $isPublicProfile = $profile && ($profile->privacy ?? 'public') !== 'private';

        $memorialName = null;
        if ($isPublicProfile) {
            $memorialName = trim(collect([
                $profile->first_name,
                $profile->middle_name,
                $profile->last_name,
            ])->filter()->implode(' '));
        }

        $profileImage = null;
        if ($isPublicProfile && $profile->images) {
            $profileImage = $profile->images->firstWhere('type', 'profile')?->path
                ?? $profile->images->firstWhere('type', 'cover')?->path;
        }

        $bannerImage = $event->banner_image ?: $profileImage;

        return [
            'memory' => $memory,
            'profile' => $profile,
            'isPublicProfile' => $isPublicProfile,
            'memorialName' => $memorialName !== '' ? $memorialName : null,
            'profileImage' => $profileImage,
            'bannerImage' => $bannerImage,
        ];
    }

    /**
     * Build shared presentation metadata for a single event view.
     *
     * @return array<string, mixed>
     */
    public static function buildPresentation(Event $event): array
    {
        $rawStreamUrl = $event->live_stream_url ?? $event->stream_link ?? $event->broadcast_link ?? null;
        $streamEmbedUrl = self::resolveEmbedStreamUrl($rawStreamUrl);
        $mapEmbedUrl = $event->map_embed_url
            ?? self::resolveMapEmbedUrl($event->map_link, $event->venue ?? $event->location ?? null);
        $eventDate = self::parseEventDate($event);
        $endDate = self::parseDateSafely($event->end_date);

        $formattedTime = null;
        if (!empty($event->start_time)) {
            try {
                $formattedTime = Carbon::parse($event->start_time)->format('g:i A');
            } catch (\Throwable) {
                $formattedTime = $event->start_time;
            }
            if (!empty($event->end_time)) {
                try {
                    $formattedTime .= ' - ' . Carbon::parse($event->end_time)->format('g:i A');
                } catch (\Throwable) {
                    $formattedTime .= ' - ' . $event->end_time;
                }
            }
        }

        $isUpcoming = $eventDate && (
            $event->event_type === 'live'
            || ($endDate ?? $eventDate)->copy()->endOfDay()->isFuture()
            || $eventDate->isToday()
        );

        return [
            'rawStreamUrl' => $rawStreamUrl,
            'streamEmbedUrl' => $streamEmbedUrl,
            'mapEmbedUrl' => $mapEmbedUrl,
            'eventDate' => $eventDate,
            'endDate' => $endDate,
            'formattedTime' => $formattedTime,
            'isUpcoming' => (bool) $isUpcoming,
            'countdownLabel' => self::buildCountdownLabel($event, $eventDate, $endDate),
            'participationMode' => self::resolveParticipationMode($event, $rawStreamUrl),
        ];
    }

    /**
     * Convert standard video URLs (YouTube / Vimeo) into iframe-safe embed URLs.
     */
    public static function resolveEmbedStreamUrl(?string $url): ?string
    {
        $url = trim((string) $url);
        if ($url === '' || !filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
        if (!in_array($scheme, ['http', 'https'], true)) {
            return null;
        }

        $host = strtolower((string) parse_url($url, PHP_URL_HOST));
        $path = trim((string) parse_url($url, PHP_URL_PATH), '/');
        $queryString = (string) parse_url($url, PHP_URL_QUERY);
        parse_str($queryString, $queryParams);

        if (str_contains($host, 'youtu.be') && $path !== '') {
            $videoId = explode('/', $path)[0];
            return "https://www.youtube.com/embed/{$videoId}?rel=0";
        }

        if (str_contains($host, 'youtube.com') || str_contains($host, 'youtube-nocookie.com')) {
            if (str_starts_with($path, 'embed/')) {
                return $url;
            }

            if (!empty($queryParams['v'])) {
                return 'https://www.youtube.com/embed/' . urlencode((string) $queryParams['v']) . '?rel=0';
            }

            $segments = explode('/', $path);
            if (count($segments) >= 2 && in_array($segments[0], ['live', 'shorts'], true)) {
                return 'https://www.youtube.com/embed/' . urlencode($segments[1]) . '?rel=0';
            }
        }

        if (str_contains($host, 'vimeo.com') && $path !== '') {
            if (str_contains($host, 'player.vimeo.com')) {
                return $url;
            }
            $videoId = explode('/', $path)[0];
            if (ctype_digit($videoId)) {
                return "https://player.vimeo.com/video/{$videoId}";
            }
        }

        return $url;
    }

    /**
     * Build a safe Google Maps embed URL from a map link, iframe snippet, or venue location.
     */
    public static function resolveMapEmbedUrl(?string $mapLink, ?string $location): ?string
    {
        $raw = trim((string) $mapLink);

        if ($raw !== '' && preg_match('/src=["\']([^"\']+)["\']/i', $raw, $iframeMatch)) {
            $raw = trim($iframeMatch[1]);
        }

        if ($raw !== '' && filter_var($raw, FILTER_VALIDATE_URL)) {
            $scheme = strtolower((string) parse_url($raw, PHP_URL_SCHEME));
            if (in_array($scheme, ['http', 'https'], true)) {
                if (str_contains($raw, 'google.com/maps/embed') || str_contains($raw, 'output=embed')) {
                    return $raw;
                }

                if (preg_match('/@(-?\d+(?:\.\d+)?),(-?\d+(?:\.\d+)?)/', $raw, $coordMatch)) {
                    return "https://maps.google.com/maps?q={$coordMatch[1]},{$coordMatch[2]}&z=15&output=embed";
                }

                $queryString = (string) parse_url($raw, PHP_URL_QUERY);
                parse_str($queryString, $queryParams);
                $mapQuery = $queryParams['q'] ?? $queryParams['ll'] ?? $queryParams['query'] ?? null;
                if (is_string($mapQuery) && trim($mapQuery) !== '') {
                    return 'https://maps.google.com/maps?q=' . urlencode(trim($mapQuery)) . '&output=embed';
                }
            }
        }

        $location = trim((string) $location);
        if ($location !== '') {
            return 'https://maps.google.com/maps?q=' . urlencode($location) . '&output=embed';
        }

        return null;
    }

    public static function parseEventDate(Event $event): ?Carbon
    {
        return self::parseDateSafely($event->start_date ?? $event->date ?? $event->event_date);
    }

    public static function parseDateSafely(mixed $value): ?Carbon
    {
        if (empty($value)) {
            return null;
        }

        if ($value instanceof Carbon) {
            return $value;
        }

        try {
            return Carbon::parse($value);
        } catch (\Throwable) {
            return null;
        }
    }

    public static function buildCountdownLabel(Event $event, ?Carbon $startDate, ?Carbon $endDate): string
    {
        if ($event->event_type === 'live') {
            return 'Live Now';
        }

        if (!$startDate) {
            return 'Scheduled Event';
        }

        $today = now()->startOfDay();
        $startDay = $startDate->copy()->startOfDay();
        $endDay = ($endDate ?? $startDate)->copy()->startOfDay();

        if ($today->betweenIncluded($startDay, $endDay)) {
            return 'Happening Today';
        }

        if ($startDay->isFuture()) {
            $days = (int) $today->diffInDays($startDay);
            return $days === 1
                ? 'Reminder: 1 Day Remaining'
                : "Reminder: {$days} Days Remaining";
        }

        return 'Concluded on ' . $endDay->format('M d, Y');
    }

    public static function resolveParticipationMode(Event $event, ?string $streamUrl): string
    {
        if (!empty($event->participation_mode)) {
            return (string) $event->participation_mode;
        }

        $hasLocation = !empty(trim((string) ($event->venue ?? $event->location ?? '')));
        $hasStream = !empty(trim((string) $streamUrl));

        if ($hasLocation && $hasStream) {
            return 'In-person & Virtual Live Streaming Available';
        }

        if ($hasStream) {
            return 'Virtual (Online Live Streaming)';
        }

        if ($hasLocation) {
            return 'In-person Gathering';
        }

        return 'Open Memorial Commemoration';
    }
}
