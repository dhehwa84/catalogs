<?php

namespace App\Services;

use App\Models\Catalogue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use RuntimeException;

class BrevoTransactionalMailer
{
    public function sendCataloguePublished(Catalogue $catalogue, string $email): void
    {
        $catalogue->loadMissing(['shop', 'category']);

        $key = config('services.brevo.key');

        if (! $key) {
            throw new RuntimeException('Brevo API key is not configured.');
        }

        $catalogueUrl = route('catalogues.show', [$catalogue->shop, $catalogue]);

        $response = Http::withHeaders([
            'accept' => 'application/json',
            'api-key' => $key,
            'content-type' => 'application/json',
        ])
            ->timeout(20)
            ->post(config('services.brevo.endpoint'), [
                'sender' => [
                    'name' => config('services.brevo.sender.name'),
                    'email' => config('services.brevo.sender.address'),
                ],
                'to' => [
                    ['email' => $email],
                ],
                'subject' => 'New special: '.$catalogue->title,
                'htmlContent' => View::make('emails.catalogue-published', [
                    'catalogue' => $catalogue,
                    'shop' => $catalogue->shop,
                    'catalogueUrl' => $catalogueUrl,
                ])->render(),
                'tags' => ['catalogue-published'],
            ]);

        if ($response->failed()) {
            throw new RuntimeException('Brevo email API failed with status '.$response->status().': '.str($response->body())->limit(500));
        }
    }
}
