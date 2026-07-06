@guest
    <div
        x-data="{
            open: false,
            dismissedUntilKey: 'newsletter-popup-dismissed-until',
            dismissMs: 24 * 60 * 60 * 1000,
            subscribedMs: 365 * 24 * 60 * 60 * 1000,
            subscribed: @js((bool) session('newsletter_subscribed')),
            init() {
                if (this.subscribed) {
                    localStorage.setItem(this.dismissedUntilKey, String(Date.now() + this.subscribedMs));
                    return;
                }

                const dismissedUntil = Number(localStorage.getItem(this.dismissedUntilKey) || 0);

                if (Date.now() > dismissedUntil) {
                    setTimeout(() => this.open = true, 1200);
                }
            },
            close() {
                this.open = false;
                localStorage.setItem(this.dismissedUntilKey, String(Date.now() + this.dismissMs));
            },
        }"
        x-cloak
        x-show="open"
        x-transition.opacity
        class="fixed inset-0 z-50 grid place-items-center bg-slate-950/50 px-4 py-6"
        role="dialog"
        aria-modal="true"
        aria-labelledby="newsletter-popup-title"
    >
        <div @click.outside="close()" class="w-full max-w-md rounded-lg bg-white p-6 shadow-xl">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 id="newsletter-popup-title" class="text-xl font-bold text-slate-950">Get specials by email</h2>
                    <p class="mt-2 text-sm text-slate-600">Receive the latest catalogue deals and shop specials in your inbox.</p>
                </div>
                <button
                    type="button"
                    @click="close()"
                    class="grid h-9 w-9 shrink-0 place-items-center rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50 hover:text-slate-900"
                    aria-label="Close newsletter popup"
                >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" action="{{ route('newsletter-subscriptions.store') }}" class="mt-5 space-y-4">
                @csrf
                <div>
                    <label for="newsletter-email" class="label">Email address</label>
                    <input
                        id="newsletter-email"
                        name="email"
                        type="email"
                        autocomplete="email"
                        required
                        class="field mt-1"
                        placeholder="you@example.com"
                    >
                    @error('email')
                        <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3">
                    <button type="button" @click="close()" class="btn-secondary">Not now</button>
                    <button type="submit" class="btn-primary">Send me specials</button>
                </div>
            </form>
        </div>
    </div>
@endguest
