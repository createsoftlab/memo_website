<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserStatusRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    /**
     * Display a listing of platform users with dedicated tabs and advanced filters.
     */
    public function index(Request $request): View
    {
        $tab = $request->get('tab', 'all'); // 'all' or 'memories'

        $query = User::with(['roles', 'events']);

        // Tab selection
        if ($tab === 'memories') {
            $query->memoryCreators();
        }

        // Apply filters (search by name, email, registration_ip, country, state, status, role)
        $query->filter($request->only(['search', 'country', 'state', 'status', 'role']));

        $users = $query->latest()->paginate(12)->withQueryString();

        // Platform statistics for overview cards and tab badges
        $stats = [
            'total' => User::count(),
            'memory_creators' => User::memoryCreators()->count(),
            'active' => User::active()->count(),
            'suspended' => User::suspended()->count(),
            'banned' => User::banned()->count(),
        ];

        // Unique countries and states for filter dropdowns
        $filterOptions = [
            'countries' => User::whereNotNull('country')->where('country', '!=', '')->distinct()->orderBy('country')->pluck('country'),
            'states' => User::whereNotNull('state')->where('state', '!=', '')->distinct()->orderBy('state')->pluck('state'),
            'roles' => Role::all(),
        ];

        return view('admin.all_users', compact('users', 'stats', 'tab', 'filterOptions'));
    }

    /**
     * Update user status (suspend, ban, or activate/reinstate).
     */
    public function updateStatus(UpdateUserStatusRequest $request, User $user): RedirectResponse|JsonResponse
    {
        // Prevent modifying root Super Admin
        if ($user->id === 1 || $user->hasRole('Super Admin')) {
            $errorMsg = 'Super Administrator accounts cannot be suspended or banned.';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $errorMsg], 403);
            }
            return back()->withErrors(['error' => $errorMsg]);
        }

        // Prevent self-lockout
        if ($user->id === auth()->id()) {
            $errorMsg = 'You cannot modify the status of your own account.';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $errorMsg], 403);
            }
            return back()->withErrors(['error' => $errorMsg]);
        }

        $oldStatus = $user->status;
        $newStatus = $request->status;

        $user->status = $newStatus;
        $user->save();

        $actionWord = match ($newStatus) {
            User::STATUS_ACTIVE => 'activated and reinstated',
            User::STATUS_SUSPENDED => 'suspended',
            User::STATUS_BANNED => 'banned permanently',
            default => 'updated',
        };

        $msg = "Account for {$user->name} ({$user->email}) has been {$actionWord}.";

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'user' => [
                    'id' => $user->id,
                    'status' => $user->status,
                    'status_badge' => $this->getStatusBadgeHtml($user->status),
                ],
            ]);
        }

        return back()->with('success', $msg);
    }

    /**
     * Toggle the memory creator flag for a user.
     */
    public function toggleMemoryCreator(User $user): RedirectResponse
    {
        $user->is_memory_creator = ! $user->is_memory_creator;
        $user->save();

        if ($user->is_memory_creator && ! $user->hasRole('Memory Created User')) {
            $user->assignRole('Memory Created User');
        }

        $stateText = $user->is_memory_creator ? 'designated as a Memorial Creator' : 'reverted to Standard User';

        return back()->with('success', "User {$user->name} has been {$stateText}.");
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }

        if ($user->id === 1 || $user->hasRole('Super Admin')) {
            return back()->withErrors(['error' => 'Super Administrator accounts cannot be deleted.']);
        }

        $userName = $user->name;
        $user->delete();

        return back()->with('success', "User account {$userName} was permanently removed.");
    }

    /**
     * Helper to render HTML status badge for AJAX responses.
     */
    private function getStatusBadgeHtml(string $status): string
    {
        return match ($status) {
            User::STATUS_ACTIVE => '<span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill"><i class="bi bi-check-circle me-1"></i> Active</span>',
            User::STATUS_SUSPENDED => '<span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill"><i class="bi bi-pause-circle me-1"></i> Suspended</span>',
            User::STATUS_BANNED => '<span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill"><i class="bi bi-x-circle me-1"></i> Banned</span>',
            default => '<span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">Unknown</span>',
        };
    }
}
