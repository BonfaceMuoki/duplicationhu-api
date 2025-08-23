<?php

namespace App\Http\Services;

use App\Models\Lead;
use App\Models\Page;
use App\Models\PageInvite;
use App\Models\User;
use App\Enums\LeadStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LeadService
{
    /**
     * Submit a new lead from a capture page
     */
    public function submitLead(array $data): array
    {
        try {
            DB::beginTransaction();

            // Find the page and referrer invite
            $page = Page::where('slug', $data['page_slug'])->firstOrFail();
            $referrerInvite = PageInvite::where('page_id', $page->id)
                ->where('handle', $data['ref'])
                ->firstOrFail();

            // Check if user already exists
            $user = User::where('email', $data['email'])->first();
            
            if (!$user) {
                // Create new user account for the lead
                $user = User::create([
                    'first_name' => $data['name'],
                    'email' => $data['email'],
                    'phone_number' => $data['whatsapp_number'] ?? null,
                    'password' => Hash::make(Str::random(16)), // Temporary password
                    'account_status' => 'active',
                ]);
            }

            // Generate unique handle for this submitter
            $submitterHandle = $this->generateUniqueHandle($page->id, $data['name']);
            
            // Create submitter invite
            $submitterInvite = PageInvite::create([
                'page_id' => $page->id,
                'user_id' => $user->id,
                'handle' => $submitterHandle,
                'clicks' => 0,
                'leads_count' => 0,
                'is_active' => true,
            ]);

            // Create the lead
            $lead = Lead::create([
                'page_id' => $page->id,
                'referrer_invite_id' => $referrerInvite->id,
                'submitter_invite_id' => $submitterInvite->id,
                'submitter_user_id' => $user->id,
                'name' => $data['name'],
                'email' => $data['email'],
                'whatsapp_number' => $data['whatsapp_number'] ?? null,
                'utm_source' => $data['utm_source'] ?? null,
                'utm_medium' => $data['utm_medium'] ?? null,
                'utm_campaign' => $data['utm_campaign'] ?? null,
                'ip_address' => $data['ip_address'] ?? null,
                'user_agent' => $data['user_agent'] ?? null,
                'status' => LeadStatus::NEW,
            ]);

            // Update closure table
            $this->updateClosureTable($referrerInvite->id, $submitterInvite->id);

            // Update counts
            $referrerInvite->increment('leads_count');
            $page->increment('views');

            DB::commit();

            // Generate personalized link for the submitter
            $myLink = url("/{$page->slug}?ref={$submitterHandle}");
            
            // Generate redirect URL for referrer
            $redirectTo = $this->generateRedirectUrl($page, $referrerInvite->handle);

            return [
                'success' => true,
                'lead' => $lead,
                'user' => $user,
                'my_link' => $myLink,
                'redirect_to' => $redirectTo,
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get leads for a specific user (their own leads + referred leads)
     */
    public function getUserLeads(User $user, int $perPage = 20): array
    {
        $leads = Lead::with(['page', 'referrerInvite.user', 'submitterInvite.user'])
            ->where(function($query) use ($user) {
                $query->where('submitter_user_id', $user->id)
                      ->orWhereHas('referrerInvite', function($q) use ($user) {
                          $q->where('user_id', $user->id);
                      });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        // Calculate user stats
        $stats = $this->calculateUserStats($user);

        return [
            'leads' => $leads,
            'stats' => $stats,
        ];
    }

    /**
     * Get all leads with filtering and pagination
     */
    public function getAllLeads(array $filters = [], int $perPage = 50): array
    {
        $query = Lead::with(['page', 'referrerInvite.user', 'submitterInvite.user', 'submitterUser']);

        // Apply filters
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['page_id'])) {
            $query->where('page_id', $filters['page_id']);
        }

        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('whatsapp_number', 'like', "%{$search}%");
            });
        }

        $leads = $query->orderBy('created_at', 'desc')->paginate($perPage);

        // Calculate overall stats
        $stats = $this->calculateOverallStats($filters);

        return [
            'leads' => $leads,
            'stats' => $stats,
        ];
    }

    /**
     * Update lead status
     */
    public function updateLeadStatus(Lead $lead, string $status, ?string $notes = null): Lead
    {
        $lead->update([
            'status' => $status,
            'notes' => $notes,
        ]);

        return $lead->fresh();
    }

    /**
     * Get lead analytics for a specific page
     */
    public function getPageLeadAnalytics(Page $page, array $filters = []): array
    {
        $query = $page->leads();

        // Apply date filters
        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        $leads = $query->get();

        // Calculate conversion rates by invite
        $inviteStats = $page->invites()
            ->withCount(['referrerLeads as total_leads'])
            ->withSum('referrerLeads', 'clicks')
            ->get()
            ->map(function($invite) {
                $invite->conversion_rate = $invite->clicks > 0 
                    ? round(($invite->total_leads / $invite->clicks) * 100, 2) 
                    : 0;
                return $invite;
            })
            ->sortByDesc('total_leads');

        // Daily lead count for charts
        $dailyLeads = $leads->groupBy(function($lead) {
            return $lead->created_at->format('Y-m-d');
        })->map->count();

        return [
            'total_leads' => $leads->count(),
            'leads_by_status' => $leads->groupBy('status')->map->count(),
            'invite_performance' => $inviteStats,
            'daily_leads' => $dailyLeads,
            'conversion_rate' => $page->views > 0 ? round(($leads->count() / $page->views) * 100, 2) : 0,
        ];
    }

    /**
     * Generate unique handle for submitter
     */
    private function generateUniqueHandle(int $pageId, string $name): string
    {
        $baseHandle = Str::slug($name);
        $handle = $baseHandle;
        $counter = 1;

        while (PageInvite::where('page_id', $pageId)->where('handle', $handle)->exists()) {
            $handle = $baseHandle . $counter;
            $counter++;
        }

        return $handle;
    }

    /**
     * Update closure table with new relationship
     */
    private function updateClosureTable(int $ancestorId, int $descendantId): void
    {
        // Insert self-reference for descendant
        DB::table('page_invite_closure')->insert([
            'ancestor_invite_id' => $descendantId,
            'descendant_invite_id' => $descendantId,
            'depth' => 0,
        ]);

        // Insert relationship to ancestor
        DB::table('page_invite_closure')->insert([
            'ancestor_invite_id' => $ancestorId,
            'descendant_invite_id' => $descendantId,
            'depth' => 1,
        ]);

        // Insert all ancestor relationships
        $ancestors = DB::table('page_invite_closure')
            ->where('descendant_invite_id', $ancestorId)
            ->where('depth', '>', 0)
            ->get();

        foreach ($ancestors as $ancestor) {
            DB::table('page_invite_closure')->insert([
                'ancestor_invite_id' => $ancestor->ancestor_invite_id,
                'descendant_invite_id' => $descendantId,
                'depth' => $ancestor->depth + 1,
            ]);
        }
    }

    /**
     * Generate redirect URL based on page platform and referrer handle
     */
    private function generateRedirectUrl(Page $page, string $referrerHandle): string
    {
        if ($page->platform_base_url) {
            return rtrim($page->platform_base_url, '/') . '/signup/' . $referrerHandle;
        }
        
        return $page->default_join_url ?? '#';
    }

    /**
     * Calculate user-specific lead statistics
     */
    private function calculateUserStats(User $user): array
    {
        $submittedLeads = Lead::where('submitter_user_id', $user->id)->count();
        $referredLeads = Lead::whereHas('referrerInvite', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();

        $totalInvites = PageInvite::where('user_id', $user->id)->count();
        $totalClicks = PageInvite::where('user_id', $user->id)->sum('clicks');

        return [
            'submitted_leads' => $submittedLeads,
            'referred_leads' => $referredLeads,
            'total_leads' => $submittedLeads + $referredLeads,
            'total_invites' => $totalInvites,
            'total_clicks' => $totalClicks,
            'conversion_rate' => $totalClicks > 0 ? round((($submittedLeads + $referredLeads) / $totalClicks) * 100, 2) : 0,
        ];
    }

    /**
     * Calculate overall lead statistics
     */
    private function calculateOverallStats(array $filters = []): array
    {
        $query = Lead::query();

        // Apply filters
        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        $totalLeads = $query->count();
        $leadsByStatus = $query->get()->groupBy('status')->map->count();

        return [
            'total_leads' => $totalLeads,
            'leads_by_status' => $leadsByStatus,
            'new_leads' => $leadsByStatus['new'] ?? 0,
            'contacted_leads' => $leadsByStatus['contacted'] ?? 0,
            'joined_leads' => $leadsByStatus['joined'] ?? 0,
        ];
    }
} 