<footer class="bg-primary text-white mt-12">
    <div class="max-w-7xl mx-auto px-4 py-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <a href="{{ route('home') }}" class="flex items-center gap-3 mb-4">
                    <img src="{{ asset('images/logo-white.png') }}" alt="{{ config('app.name') }}" class="h-10">
                    <span class="font-heading text-xl">{{ config('app.name') }}</span>
                </a>
                <p class="text-slate-200">Chúng tôi cung cấp giải pháp xây dựng trọn gói: thiết kế, thi công và giám sát thi công. Uy tín - Chất lượng - An toàn.</p>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-4">Dịch Vụ</h3>
                <ul class="space-y-2 text-slate-200">
                    <li><a href="#" class="hover:underline">Thiết kế kiến trúc</a></li>
                    <li><a href="#" class="hover:underline">Thi công xây dựng</a></li>
                    <li><a href="#" class="hover:underline">Hoàn thiện nội thất</a></li>
                    <li><a href="#" class="hover:underline">Tư vấn và giám sát</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-4">Liên Hệ</h3>
                <ul class="space-y-2 text-slate-200">
                    <li>Email: <a href="mailto:{{ setting('email', 'contact@example.com') }}" class="hover:underline">{{ setting('email', 'contact@example.com') }}</a></li>
                    <li>Hotline: <a href="tel:{{ setting('phone', '0123456789') }}" class="hover:underline">{{ setting('phone', '0123 456 789') }}</a></li>
                    <li>Địa chỉ: {{ setting('address', 'Hà Nội, Việt Nam') }}</li>
                </ul>
            </div>
        </div>

        <div class="border-t border-primary/60 mt-8 pt-6 text-center text-slate-300">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</footer>
