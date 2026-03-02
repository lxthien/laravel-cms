@extends('frontend.layouts.app')

@section('title', setting('page_title', 'Trang chủ'))
@section('full-width', true)

<x-seo-meta title="{{ setting('page_title') }}" description="{{ setting('page_description') }}"
    keywords="{{ setting('page_keywords') }}" robots="index, follow" type="website" />

@section('content')
    <!-- 1. Hero Slider -->
    <section class="home-hero" style="background-image: url('{{ asset('images/hero-project.jpg') }}');">
        <div class="hero-content">
            <h1 class="hero-title">Xây Dựng Khang Trang - Vững Vàng Tương Lai</h1>
            <p class="hero-subtitle">Chúng tôi chuyên cung cấp dịch vụ xây nhà trọn gói, sửa chữa cải tạo nhà và thiết kế
                kiến trúc uy tín chuyên nghiệp nhất.</p>
        </div>
    </section>

    <!-- 2. CTA Bar -->
    <section class="home-cta-bar">
        <div class="max-w-7xl mx-auto px-4 cta-inner">
            <div class="cta-text">
                BẠN ĐANG MUỐN THI CÔNG DỰ ÁN XÂY DỰNG? HÃY LIÊN HỆ NGAY ĐẾN CHÚNG TÔI ĐỂ ĐƯỢC TƯ VẤN MIỄN PHÍ!
            </div>
            <a href="tel:{{ setting('contact_phone', '0974.776.305') }}" class="cta-btn cursor-pointer">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                    </path>
                </svg>
                GỌI NGAY: {{ setting('contact_phone', '0974.776.305') }}
            </a>
        </div>
    </section>

    <!-- 3. Quick Links (4 Cột Tròn) -->
    <section class="home-quick-links">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <a href="#" class="quick-link-item">
                    <div class="img-wrapper">
                        <!-- Use placeholder or actual setting/image -->
                        <img src="https://images.unsplash.com/photo-1541888085994-3d0d82944b0e?w=300&h=300&fit=crop"
                            alt="Xây Nhà Trọn Gói">
                    </div>
                    <div class="link-title">Xây Nhà Trọn Gói</div>
                </a>
                <a href="#" class="quick-link-item">
                    <div class="img-wrapper">
                        <img src="https://images.unsplash.com/photo-1503387762-592deb58ef4e?w=300&h=300&fit=crop"
                            alt="Thiết Kế Kiến Trúc">
                    </div>
                    <div class="link-title">Thiết Kế Kiến Trúc</div>
                </a>
                <a href="#" class="quick-link-item">
                    <div class="img-wrapper">
                        <img src="https://images.unsplash.com/photo-1581094794329-c8112a89af12?w=300&h=300&fit=crop"
                            alt="Sửa Chữa Nhà">
                    </div>
                    <div class="link-title">Sửa Chữa Nhà</div>
                </a>
                <a href="#" class="quick-link-item">
                    <div class="img-wrapper">
                        <img src="https://images.unsplash.com/photo-1513694203232-719a280e022f?w=300&h=300&fit=crop"
                            alt="Nội Thất">
                    </div>
                    <div class="link-title">Thiết Kế Nội Thất</div>
                </a>
            </div>
        </div>
    </section>

    <!-- 4. Why Choose Us -->
    <section class="home-why-choose">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="section-title">Tại Sao Khách Hàng <span>Chọn Chúng Tôi</span></h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-6">
                <div class="reason-box">
                    <div class="icon-circle">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="reason-content">
                        <h4>Chất lượng hàng đầu</h4>
                        <p>Kiểm soát vật tư nghiêm ngặt, thi công đúng kỹ thuật bản vẽ.</p>
                    </div>
                </div>
                <div class="reason-box">
                    <div class="icon-circle">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="reason-content">
                        <h4>Thi công đúng tiến độ</h4>
                        <p>Cam kết bàn giao công trình đúng thời hạn trong hợp đồng.</p>
                    </div>
                </div>
                <div class="reason-box">
                    <div class="icon-circle">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="reason-content">
                        <h4>Báo giá minh bạch</h4>
                        <p>Không phát sinh chi phí sau khi đã ký hợp đồng trọn gói.</p>
                    </div>
                </div>
                <div class="reason-box">
                    <div class="icon-circle">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <div class="reason-content">
                        <h4>Đội ngũ chuyên nghiệp</h4>
                        <p>Đội ngũ KS, KTS tận tâm, giải quyết các vấn đề cực lùi và tinh gọn.</p>
                    </div>
                </div>
                <div class="reason-box">
                    <div class="icon-circle">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                    </div>
                    <div class="reason-content">
                        <h4>Mẫu mã đa dạng</h4>
                        <p>Liên tục cập nhật các xu hướng thiết kế mới mang dấu ấn riêng.</p>
                    </div>
                </div>
                <div class="reason-box">
                    <div class="icon-circle">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="reason-content">
                        <h4>Sự hài lòng tuyệt đối</h4>
                        <p>Mong muốn mang đến tổ ấm lâu dài cho mọi nhà.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. Featured Posts (Dịch vụ nổi bật) -->
    <section class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4">

            <div class="section-heading-custom">
                <h3 class="heading-title">
                    <svg class="title-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    Dịch Vụ Nổi Bật
                </h3>
                <span class="heading-arrow">&raquo;</span>
                <div class="heading-tabs">
                    <a href="#" class="tab-pill active">Xây nhà trọn gói</a>
                    <a href="#" class="tab-pill">Kiến trúc</a>
                    <a href="#" class="tab-pill">Sửa chữa</a>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredPosts as $index => $post)
                    @if($index < 4) {{-- Show max 4 cards per row --}}
                        <article class="service-card">
                            <a href="{{ url($post->full_path) }}" class="card-img block">
                                <img src="{{ $post->featured_image ? asset('storage/' . $post->featured_image) : 'https://placehold.co/400x300/e2e8f0/475569?text=No+Image' }}"
                                    alt="{{ $post->title }}">
                                <div class="card-overlay">
                                    <span class="view-btn">Xem chi tiết</span>
                                </div>
                            </a>
                            <div class="card-content">
                                <h3 class="card-title">
                                    <a href="{{ url($post->full_path) }}">{{ $post->title }}</a>
                                </h3>
                                <div class="card-desc">
                                    {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 120) }}
                                </div>
                            </div>
                        </article>
                    @endif
                @endforeach
            </div>

        </div>
    </section>

    <!-- 6. Latest Posts (Tin tức - Kinh nghiệm) -->
    <section class="bg-[#f8f9fa] py-16">
        <div class="max-w-7xl mx-auto px-4">

            <div class="section-heading-custom">
                <h3 class="heading-title">
                    <svg class="title-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H14">
                        </path>
                    </svg>
                    Tin Tức & Kinh Nghiệm
                </h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($latestPosts as $index => $post)
                    @if($index < 4)
                        <article class="service-card">
                            <a href="{{ url($post->full_path) }}" class="card-img block">
                                <img src="{{ $post->featured_image ? asset('storage/' . $post->featured_image) : 'https://placehold.co/400x300/e2e8f0/475569?text=No+Image' }}"
                                    alt="{{ $post->title }}">
                            </a>
                            <div class="card-content">
                                <h3 class="card-title">
                                    <a href="{{ url($post->full_path) }}">{{ $post->title }}</a>
                                </h3>
                                <div class="card-desc">
                                    {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 120) }}
                                </div>
                            </div>
                        </article>
                    @endif
                @endforeach
            </div>

        </div>
    </section>

    <!-- 7. Customer Testimonials (Ý Kiến Khách Hàng) -->
    <section class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4">

            <div class="section-heading-custom">
                <h3 class="heading-title">
                    <svg class="title-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                        </path>
                    </svg>
                    Ý Kiến Khách Hàng
                </h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Video 1 (Placeholder ID: jNQXAC9IVRw - YouTube) -->
                <div class="video-card" data-youtube-id="jNQXAC9IVRw">
                    <img src="https://img.youtube.com/vi/jNQXAC9IVRw/maxresdefault.jpg" alt="Đánh giá khách hàng Quận 12">
                    <div class="play-btn">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M8 5v14l11-7z" />
                        </svg>
                    </div>
                </div>

                <!-- Video 2 -->
                <div class="video-card" data-youtube-id="LXb3EKWsInQ">
                    <img src="https://img.youtube.com/vi/LXb3EKWsInQ/maxresdefault.jpg" alt="Bàn giao nhà trọn gói Gò Vấp">
                    <div class="play-btn">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M8 5v14l11-7z" />
                        </svg>
                    </div>
                </div>

                <!-- Video 3 -->
                <div class="video-card" data-youtube-id="dQw4w9WgXcQ">
                    <img src="https://img.youtube.com/vi/dQw4w9WgXcQ/maxresdefault.jpg" alt="Cảm nhận từ chủ nhà">
                    <div class="play-btn">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M8 5v14l11-7z" />
                        </svg>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Lightbox Modal for YouTube Videos -->
    <div class="youtube-modal" id="youtubeModal">
        <div class="modal-content">
            <button class="modal-close" id="closeYoutubeModal" aria-label="Close modal">&times;</button>
            <div class="video-container" id="youtubeVideoContainer">
                <!-- iframe will be injected here by JS -->
            </div>
        </div>
    </div>

@endsection