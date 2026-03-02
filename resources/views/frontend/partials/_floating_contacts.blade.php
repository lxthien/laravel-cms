{{-- Floating Element: Social Contacts and Sticky Hotline --}}
<div class="floating-socials">
    {{-- Replace URLs with actual settings or values --}}
    @if(setting('messenger_url', '#'))
        <a href="{{ setting('messenger_url', '#') }}" target="_blank" class="social-btn btn-messenger"
            aria-label="Messenger">
            <svg viewBox="0 0 36 36">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M18 3.013C9.728 3.013 3 9.245 3 16.923c0 4.385 2.185 8.291 5.518 10.84v4.717c0 .542.593.843 1.05.53l4.782-3.266c1.178.332 2.428.513 3.73.513 8.272 0 14.97-6.232 14.97-13.91C32.97 9.245 26.273 3 18 3zm-1.84 17.5l-3.328-5.267a1.693 1.693 0 00-2.484-.46l-5.3 4.22c-.676.54-1.488-.327-.962-1.023l6.096-8.03c.53-.7 1.484-1.01 2.336-.763l3.328 5.268a1.693 1.693 0 002.484.459l5.3-4.22c.676-.539 1.488.328.962 1.023l-6.096 8.03a1.693 1.693 0 00-2.336.762z">
                </path>
            </svg>
        </a>
    @endif
    @if(setting('zalo_url', '#'))
        <a href="{{ setting('zalo_url', '#') }}" target="_blank" class="social-btn btn-zalo" aria-label="Zalo">
            {{-- Custom Zalo Text Icon since SVG isn't simple --}}
            <span style="font-weight: bold; font-size: 14px;">Zalo</span>
        </a>
    @endif
</div>

<div class="sticky-hotline">
    <a href="tel:{{ setting('contact_phone', '0974.776.305') }}" class="hotline-btn">
        <div class="hotline-icon">
            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                style="width: 20px; height: 20px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
            </svg>
        </div>
        <span>{{ setting('contact_phone', '0974.776.305') }}</span>
    </a>
</div>

{{-- Back to top button --}}
<button class="back-totop" id="backToTopBtn" aria-label="Go to top">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 24px; height: 24px;">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
    </svg>
</button>