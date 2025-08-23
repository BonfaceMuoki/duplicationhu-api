<?php

namespace App\Enums;

enum LeadStatus: string
{
    case NEW = 'new';
    case CONTACTED = 'contacted';
    case JOINED = 'joined';

    public function label(): string
    {
        return match($this) {
            self::NEW => 'New Lead',
            self::CONTACTED => 'Contacted',
            self::JOINED => 'Joined Platform',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::NEW => 'blue',
            self::CONTACTED => 'yellow',
            self::JOINED => 'green',
        };
    }
} 