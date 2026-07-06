<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsletterSubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email:rfc', 'max:255'],
        ]);

        NewsletterSubscriber::query()->firstOrCreate(
            ['email' => Str::lower($validated['email'])],
            [
                'subscribed_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => Str::limit((string) $request->userAgent(), 1000, ''),
            ],
        );

        return back()
            ->with('status', 'Thanks. You will receive specials by email.')
            ->with('newsletter_subscribed', true);
    }
}
