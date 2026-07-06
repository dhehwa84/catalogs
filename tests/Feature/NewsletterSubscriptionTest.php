<?php

namespace Tests\Feature;

use App\Models\NewsletterSubscriber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsletterSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitor_can_subscribe_to_specials_by_email(): void
    {
        $response = $this->from('/')->post(route('newsletter-subscriptions.store'), [
            'email' => 'Deals@Example.com',
        ]);

        $response
            ->assertRedirect('/')
            ->assertSessionHas('newsletter_subscribed', true);

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'deals@example.com',
        ]);
    }

    public function test_existing_subscriber_is_not_duplicated(): void
    {
        NewsletterSubscriber::create([
            'email' => 'deals@example.com',
            'subscribed_at' => now()->subDay(),
        ]);

        $this->post(route('newsletter-subscriptions.store'), [
            'email' => 'deals@example.com',
        ]);

        $this->assertSame(1, NewsletterSubscriber::where('email', 'deals@example.com')->count());
    }

    public function test_super_admin_can_view_paginated_subscriber_list(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        NewsletterSubscriber::create([
            'email' => 'deals@example.com',
            'subscribed_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.newsletter-subscribers.index'))
            ->assertOk()
            ->assertSee('deals@example.com')
            ->assertSee('Newsletter subscribers');
    }

    public function test_non_admin_cannot_view_subscriber_list(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.newsletter-subscribers.index'))
            ->assertForbidden();
    }
}
