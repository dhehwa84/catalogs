<?php

namespace Tests\Feature;

use App\Models\Catalogue;
use App\Models\CatalogueCategory;
use App\Models\NewsletterSubscriber;
use App\Models\Shop;
use App\Models\User;
use App\Services\CatalogueSubscriberNotifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CatalogueSubscriberNotifierTest extends TestCase
{
    use RefreshDatabase;

    public function test_published_catalogue_is_emailed_to_newsletter_subscribers(): void
    {
        config([
            'services.brevo.key' => 'test-key',
            'services.brevo.endpoint' => 'https://api.brevo.com/v3/smtp/email',
            'services.brevo.sender.address' => 'info@example.com',
            'services.brevo.sender.name' => 'PulsePoint',
        ]);
        Http::fake([
            'api.brevo.com/v3/smtp/email' => Http::response(['messageId' => 'test-message-id'], 201),
        ]);
        $catalogue = $this->publishedCatalogue();

        NewsletterSubscriber::create([
            'email' => 'deals@example.com',
            'subscribed_at' => now(),
        ]);

        app(CatalogueSubscriberNotifier::class)->notifyIfNeeded($catalogue);

        Http::assertSent(function ($request) use ($catalogue) {
            return $request->url() === 'https://api.brevo.com/v3/smtp/email'
                && $request->hasHeader('api-key', 'test-key')
                && $request['sender']['email'] === 'info@example.com'
                && $request['sender']['name'] === 'PulsePoint'
                && $request['to'][0]['email'] === 'deals@example.com'
                && $request['subject'] === 'New special: '.$catalogue->title
                && str_contains($request['htmlContent'], route('catalogues.show', [$catalogue->shop, $catalogue]));
        });

        $this->assertNotNull($catalogue->fresh()->subscribers_notified_at);
    }

    public function test_catalogue_is_not_emailed_twice(): void
    {
        Http::fake();
        $catalogue = $this->publishedCatalogue([
            'subscribers_notified_at' => now(),
        ]);

        NewsletterSubscriber::create([
            'email' => 'deals@example.com',
            'subscribed_at' => now(),
        ]);

        app(CatalogueSubscriberNotifier::class)->notifyIfNeeded($catalogue);

        Http::assertNothingSent();
    }

    public function test_draft_catalogue_is_not_emailed(): void
    {
        Http::fake();
        $catalogue = $this->publishedCatalogue([
            'status' => 'draft',
            'published_at' => null,
        ]);

        NewsletterSubscriber::create([
            'email' => 'deals@example.com',
            'subscribed_at' => now(),
        ]);

        app(CatalogueSubscriberNotifier::class)->notifyIfNeeded($catalogue);

        Http::assertNothingSent();
    }

    private function publishedCatalogue(array $overrides = []): Catalogue
    {
        $user = User::factory()->create();
        $shop = Shop::create([
            'name' => 'OK Zimbabwe',
            'slug' => 'ok-zimbabwe',
            'status' => 'active',
            'created_by' => $user->id,
        ]);
        $category = CatalogueCategory::create(['name' => 'Groceries']);

        return Catalogue::create(array_merge([
            'shop_id' => $shop->id,
            'created_by' => $user->id,
            'catalogue_category_id' => $category->id,
            'title' => 'Weekend Grocery Deals',
            'slug' => 'weekend-grocery-deals',
            'description' => 'Fresh specials for the weekend.',
            'valid_from' => now()->subDay(),
            'valid_to' => now()->addWeek(),
            'status' => 'published',
            'published_at' => now(),
            'cover_image' => 'covers/one.jpg',
            'file_path' => 'catalogues/one.pdf',
            'file_type' => 'pdf',
        ], $overrides));
    }
}
