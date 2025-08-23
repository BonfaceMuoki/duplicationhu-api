<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PageInvite extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'user_id',
        'handle',
        'join_url',
        'clicks',
        'leads_count',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'clicks' => 'integer',
        'leads_count' => 'integer',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function referredLeads(): HasMany
    {
        return $this->hasMany(Lead::class, 'referrer_invite_id');
    }

    public function submittedLeads(): HasMany
    {
        return $this->hasMany(Lead::class, 'submitter_invite_id');
    }

    public function ancestors(): BelongsToMany
    {
        return $this->belongsToMany(
            PageInvite::class,
            'page_invite_closure',
            'descendant_invite_id',
            'ancestor_invite_id'
        )->withPivot('depth');
    }

    public function descendants(): BelongsToMany
    {
        return $this->belongsToMany(
            PageInvite::class,
            'page_invite_closure',
            'ancestor_invite_id',
            'descendant_invite_id'
        )->withPivot('depth');
    }
}