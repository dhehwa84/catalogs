<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['email', 'subscribed_at', 'ip_address', 'user_agent'])]
class NewsletterSubscriber extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'subscribed_at' => 'datetime',
        ];
    }
}
