@extends('frontend.layouts.app')

@section('title', 'Liên hệ')
@section('full-width', true)

@section('breadcrumb')
    <div class="max-w-7xl mx-auto px-4 mt-4">
        @include('frontend.partials._breadcrumb', ['breadcrumbs' => [['title' => 'Liên hệ', 'url' => route('contact')]]])
    </div>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-4">
        {{-- Section Heading --}}
        <div class="section-heading-custom">
            <h1 class="heading-title">
                <svg class="title-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v9a2 2 0 002 2z">
                    </path>
                </svg>
                Liên hệ với chúng tôi
            </h1>
            <span class="heading-arrow">»</span>
            <div class="heading-tabs"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mt-8">
            {{-- Left Column: Contact Info & Map --}}
            <div class="space-y-8">
                <div class="prose max-w-none text-slate-600 mb-6">
                    <p>Chúng tôi luôn sẵn sàng lắng nghe và giải đáp mọi thắc mắc của quý khách. Hãy liên hệ với chúng tôi
                        qua các kênh dưới đây hoặc gửi tin nhắn trực tiếp qua form.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    {{-- Address --}}
                    <div class="flex gap-4">
                        <div
                            class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-accent flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 uppercase text-xs tracking-wider mb-1">Địa chỉ</h4>
                            <p class="text-sm text-slate-600">{{ setting('contact_address') }}</p>
                        </div>
                    </div>

                    {{-- Hotline --}}
                    <div class="flex gap-4">
                        <div
                            class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-accent flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 uppercase text-xs tracking-wider mb-1">Hotline</h4>
                            <a href="tel:{{ setting('contact_phone') }}"
                                class="text-sm text-accent font-bold hover:underline">{{ setting('contact_phone') }}</a>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="flex gap-4">
                        <div
                            class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-accent flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v9a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 uppercase text-xs tracking-wider mb-1">Email</h4>
                            <a href="mailto:{{ setting('contact_email') }}"
                                class="text-sm text-slate-600 hover:text-accent transition-colors">{{ setting('contact_email') }}</a>
                        </div>
                    </div>

                    {{-- Working Hours --}}
                    <div class="flex gap-4">
                        <div
                            class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-accent flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 uppercase text-xs tracking-wider mb-1">Giờ làm việc</h4>
                            <p class="text-sm text-slate-600">Thứ 2 - Thứ 7: 08:00 - 17:00</p>
                        </div>
                    </div>
                </div>

                {{-- Map Placeholder (Can be replaced with actual Google Map Embed) --}}
                <div
                    class="w-full h-80 bg-slate-200 rounded-xl overflow-hidden shadow-inner relative group border-4 border-white shadow-lg">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.86385588142!2d105.7892673!3d21.0381278!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab3583583583%3A0x1c1c1c1c1c1c1c1c!2zSGFub2ksIFZpZXRuYW0!5e0!3m2!1sen!2s!4v1620000000000!5m2!1sen!2s"
                        width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>

            {{-- Right Column: Contact Form --}}
            <div>
                <div class="bg-white p-8 rounded-2xl shadow-xl border border-slate-100">
                    <h3 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                        <span class="w-2 h-8 bg-accent rounded-full inline-block"></span>
                        Gửi yêu cầu tư vấn
                    </h3>

                    @if(session('success'))
                        <div
                            class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('contact.submit') }}" class="space-y-5">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-sm font-semibold text-slate-700 ml-1">Họ và tên *</label>
                                <input type="text" name="name" class="form-input" placeholder="Nguyễn Văn A"
                                    value="{{ old('name') }}" required>
                                @error('name')<small class="text-red-500 text-xs ml-1">{{ $message }}</small>@enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-sm font-semibold text-slate-700 ml-1">Số điện thoại *</label>
                                <input type="text" name="phone" class="form-input" placeholder="0123 456 789"
                                    value="{{ old('phone') }}" required>
                                @error('phone')<small class="text-red-500 text-xs ml-1">{{ $message }}</small>@enderror
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-slate-700 ml-1">Email</label>
                            <input type="email" name="email" class="form-input" placeholder="example@gmail.com"
                                value="{{ old('email') }}">
                            @error('email')<small class="text-red-500 text-xs ml-1">{{ $message }}</small>@enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-slate-700 ml-1">Nội dung yêu cầu *</label>
                            <textarea name="message" class="form-textarea" rows="4"
                                placeholder="Quý khách cần tư vấn về dịch vụ nào?" required>{{ old('message') }}</textarea>
                            @error('message')<small class="text-red-500 text-xs ml-1">{{ $message }}</small>@enderror
                        </div>

                        <button type="submit"
                            class="w-full bg-accent hover:bg-orange-600 text-white font-bold py-4 rounded-xl shadow-lg shadow-orange-200 transition-all duration-300 transform hover:-translate-y-1 active:scale-95 uppercase tracking-wide">
                            Gửi liên hệ ngay
                        </button>

                        <p class="text-[11px] text-center text-slate-400 mt-4 leading-relaxed">
                            * Thông tin của bạn sẽ được bảo mật tuyệt đối. <br>
                            Nhân viên tư vấn sẽ liên hệ lại trong vòng 15-30 phút.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection