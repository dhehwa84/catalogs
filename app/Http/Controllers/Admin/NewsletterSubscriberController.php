<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterSubscriberController extends Controller
{
    public function index(Request $request)
    {
        if (! $request->user()?->isSuperAdmin()) {
            abort(403);
        }

        $subscribers = NewsletterSubscriber::query()
            ->latest('subscribed_at')
            ->paginate(20);

        return view('admin.newsletter-subscribers.index', compact('subscribers'));
    }
}
