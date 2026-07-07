<?php

namespace App\Services;

use App\Models\Catalogue;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\Log;
use Throwable;

class CatalogueSubscriberNotifier
{
    public function __construct(protected BrevoTransactionalMailer $mailer)
    {
    }

    public function notifyIfNeeded(Catalogue $catalogue): void
    {
        $catalogue->refresh()->loadMissing(['shop', 'category']);

        if ($catalogue->status !== 'published' || $catalogue->subscribers_notified_at) {
            return;
        }

        $successfulSends = 0;
        $subscriberCount = 0;

        NewsletterSubscriber::query()
            ->orderBy('id')
            ->chunkById(100, function ($subscribers) use ($catalogue, &$successfulSends, &$subscriberCount) {
                foreach ($subscribers as $subscriber) {
                    $subscriberCount++;

                    try {
                        $this->mailer->sendCataloguePublished($catalogue, $subscriber->email);
                        $successfulSends++;
                    } catch (Throwable $exception) {
                        Log::warning('Failed to send catalogue special email.', [
                            'catalogue_id' => $catalogue->id,
                            'subscriber_id' => $subscriber->id,
                            'error' => $exception->getMessage(),
                        ]);
                    }
                }
            });

        if ($subscriberCount === 0 || $successfulSends > 0) {
            $catalogue->forceFill(['subscribers_notified_at' => now()])->save();
        }
    }
}
