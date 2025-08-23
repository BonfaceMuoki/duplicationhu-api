<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\PageInvite;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageInviteController extends Controller
{
    /**
     * Track click on invite link
     */
    public function trackClick(Request $request)
    {
        $request->validate([
            'page_slug' => 'required|string|exists:pages,slug',
            'ref' => 'required|string',
        ]);

        $page = Page::where('slug', $request->page_slug)->firstOrFail();
        $invite = PageInvite::where('page_id', $page->id)
            ->where('handle', $request->ref)
            ->firstOrFail();

        // Increment click count
        $invite->increment('clicks');

        return response()->json([
            'success' => true,
            'message' => 'Click tracked successfully'
        ]);
    }

    /**
     * Get invite performance for a user
     */
    public function myInvites(Request $request)
    {
        $user = auth()->user();
        
        $invites = PageInvite::with(['page'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate totals
        $totalClicks = $invites->sum('clicks');
        $totalLeads = $invites->sum('leads_count');
        $conversionRate = $totalClicks > 0 ? round(($totalLeads / $totalClicks) * 100, 2) : 0;

        return response()->json([
            'success' => true,
            'data' => [
                'invites' => $invites,
                'stats' => [
                    'total_clicks' => $totalClicks,
                    'total_leads' => $totalLeads,
                    'conversion_rate' => $conversionRate,
                ]
            ]
        ]);
    }

    /**
     * Get all invites for admin
     */
    public function allInvites(Request $request)
    {
        $invites = PageInvite::with(['page', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return response()->json([
            'success' => true,
            'data' => $invites
        ]);
    }

    /**
     * Create new invite for a page
     */
    public function createInvite(Request $request)
    {
        $request->validate([
            'page_id' => 'required|exists:pages,id',
            'handle' => 'required|string|max:50',
            'join_url' => 'nullable|url|max:500',
        ]);

        $page = Page::findOrFail($request->page_id);
        
        // Check if user has permission to create invites for this page
        if (auth()->user()->id !== $page->user_id && !auth()->user()->hasRole('super admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to create invites for this page'
            ], 403);
        }

        // Check if handle already exists for this page
        if (PageInvite::where('page_id', $request->page_id)
            ->where('handle', $request->handle)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Handle already exists for this page'
            ], 422);
        }

        $invite = PageInvite::create([
            'page_id' => $request->page_id,
            'user_id' => auth()->user()->id,
            'handle' => $request->handle,
            'join_url' => $request->join_url,
            'clicks' => 0,
            'leads_count' => 0,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Invite created successfully',
            'data' => $invite
        ], 201);
    }

    /**
     * Update invite
     */
    public function updateInvite(Request $request, PageInvite $invite)
    {
        $request->validate([
            'handle' => 'sometimes|string|max:50',
            'join_url' => 'sometimes|nullable|url|max:500',
            'is_active' => 'sometimes|boolean',
        ]);

        // Check if user has permission to update this invite
        if (auth()->user()->id !== $invite->user_id && !auth()->user()->hasRole('super admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to update this invite'
            ], 403);
        }

        // Check if new handle conflicts with existing ones
        if (isset($request->handle) && $request->handle !== $invite->handle) {
            if (PageInvite::where('page_id', $invite->page_id)
                ->where('handle', $request->handle)
                ->where('id', '!=', $invite->id)
                ->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Handle already exists for this page'
                ], 422);
            }
        }

        $invite->update($request->only(['handle', 'join_url', 'is_active']));

        return response()->json([
            'success' => true,
            'message' => 'Invite updated successfully',
            'data' => $invite
        ]);
    }

    /**
     * Delete invite
     */
    public function deleteInvite(PageInvite $invite)
    {
        // Check if user has permission to delete this invite
        if (auth()->user()->id !== $invite->user_id && !auth()->user()->hasRole('super admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to delete this invite'
            ], 403);
        }

        $invite->delete();

        return response()->json([
            'success' => true,
            'message' => 'Invite deleted successfully'
        ]);
    }

    /**
     * Get referral tree for a specific invite
     */
    public function getReferralTree(Request $request, PageInvite $invite)
    {
        $descendants = $invite->descendants()
            ->with(['user', 'page'])
            ->where('depth', '>', 0)
            ->orderBy('depth')
            ->get();

        $ancestors = $invite->ancestors()
            ->with(['user', 'page'])
            ->where('depth', '>', 0)
            ->orderBy('depth')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'invite' => $invite->load(['user', 'page']),
                'upline' => $ancestors,
                'downline' => $descendants,
            ]
        ]);
    }
} 