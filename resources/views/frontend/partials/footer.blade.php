<footer class="site-footer mt-12">
    <!-- 1. Top Footer -->
    <div class="max-w-7xl mx-auto px-4 footer-top">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Cột 1: Thông tin liên hệ -->
            <div>
                <h3 class="footer-heading">{{ config('app.name', 'XÂY DỰNG KIM ANH') }}</h3>
                <ul class="footer-contact-list">
                    @if(setting('contact_address'))
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            <span>{{ setting('contact_address', '50I Trần Thị Bảy, KP3, P. Hiệp Thành, Quận 12, TP.HCM') }}</span>
                        </li>
                    @endif

                    @if(setting('contact_phone'))
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                </path>
                            </svg>
                            <a href="tel:{{ setting('contact_phone') }}">{{ setting('contact_phone') }}</a>
                        </li>
                    @endif

                    @if(setting('contact_email'))
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                </path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                            <a href="mailto:{{ setting('contact_email') }}">{{ setting('contact_email') }}</a>
                        </li>
                    @endif

                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="2" y1="12" x2="22" y2="12"></line>
                            <path
                                d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z">
                            </path>
                        </svg>
                        <a href="{{ url('/') }}">{{ parse_url(url('/'), PHP_URL_HOST) ?? 'xaydungkimanh.com' }}</a>
                    </li>
                </ul>
            </div>

            <!-- Cột 2: Về Chúng Tôi -->
            <div>
                <h3 class="footer-heading">Về chúng tôi</h3>
                <ul class="footer-quick-links">
                    <li><a href="#">Giới thiệu công ty</a></li>
                    <li><a href="#">Hồ sơ năng lực</a></li>
                    <li><a href="#">Quy trình làm việc</a></li>
                    <li><a href="#">Chính sách bảo mật</a></li>
                    <li><a href="#">Chính sách bảo hành</a></li>
                </ul>
            </div>

            <!-- Cột 3: Dịch vụ -->
            <div>
                <h3 class="footer-heading">Dịch vụ chính</h3>
                <ul class="footer-service-links">
                    <li><a href="#">Xây nhà trọn gói</a></li>
                    <li><a href="#">Xây nhà phần thô</a></li>
                    <li><a href="#">Sửa nhà trọn gói</a></li>
                    <li><a href="#">Sửa nhà nâng tầng</a></li>
                    <li><a href="#">Thiết kế kiến trúc</a></li>
                </ul>
            </div>

            <!-- Cột 4: Fanpage -->
            <div>
                <h3 class="footer-heading">Fanpage</h3>
                <div class="bg-white/10 rounded overflow-hidden min-h-[130px] flex items-center justify-center">
                    {{-- Replace with Facebook Iframe code from setting plugin if available --}}
                    @if(setting('facebook_iframe'))
                        {!! setting('facebook_iframe') !!}
                    @else
                        <!-- Placeholder if no iframe setting -->
                        <span class="text-sm text-white/50 text-center block w-full p-4">Cập nhật mã Iframe Facebook trong
                            cài đặt.</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Middle Footer -->
    <div class="max-w-7xl mx-auto px-4">
        <div class="footer-middle">
            <a href="{{ route('home') }}" class="footer-logo">
                <img src="{{ setting('site_logo') ? asset('storage/' . setting('site_logo')) : asset('images/logo-white.png') }}"
                    alt="{{ config('app.name') }}">
            </a>
            <p class="footer-intro">
                Chúng tôi tự hào là đơn vị uy tín hàng đầu trong lĩnh vực thiết kế và thi công xây dựng nhà ở dân dụng
                tại TP.HCM và các tỉnh lân cận. Cam kết chất lượng, tiến độ và minh bạch vật tư.
            </p>
        </div>
    </div>

    <!-- 3. Bottom Footer -->
    <div class="max-w-7xl mx-auto px-4">
        <div class="footer-bottom">
            <div class="bottom-inner">
                <div class="copyright">
                    Copyright &copy; {{ date('Y') }} <strong>{{ config('app.name', 'Xây Dựng Kim Anh') }}</strong>. All
                    rights reserved.
                </div>
                <div class="dmca-badge">
                    <!-- Standard DMCA placeholder image -->
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="https://images.dmca.com/Badges/dmca-badge-w150-5x1-06.png?ID=placeholder"
                            alt="DMCA.com Protection Status" />
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>