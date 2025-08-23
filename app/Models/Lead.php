<?php

namespace App\Models;

use App\Enums\LeadStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'referrer_invite_id',
        'submitter_invite_id',
        'submitter_user_id',
        'name',
        'email',
        'whatsapp_number',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'ip_address',
        'user_agent',
        'status',
        'notes',
    ];

    protected $casts = [
        'page_id' => 'integer',
        'referrer_invite_id' => 'integer',
        'submitter_invite_id' => 'integer',
        'submitter_user_id' => 'integer',
        'status' => LeadStatus::class,
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function referrerInvite(): BelongsTo
    {
        return $this->belongsTo(PageInvite::class, 'referrer_invite_id');
    }

    public function submitterInvite(): BelongsTo
    {
        return $this->belongsTo(PageInvite::class, 'submitter_invite_id');
    }

    public function submitterUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitter_user_id');
    }
}