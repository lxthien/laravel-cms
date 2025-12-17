{{-- Basic Meta Tags --}}
<title>{{ $getFullTitle() }}</title>
<meta name="description" content="{{ $description }}">
@if($keywords)
    <meta name="keywords" content="{{ $keywords }}">
@endif
<meta name="robots" content="{{ $robots }}">
<link rel="canonical" href="{{ $canonicalUrl }}">
<meta name="author" content="{{ $author }}">
{{-- Open Graph / Facebook Meta Tags --}}
<meta property="og:type" content="{{ $type }}">
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $image }}">
<meta property="og:image:secure_url" content="{{ $image }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:site_name" content="{{ setting('site_name') }}">
<meta property="og:locale" content="vi_VN">
{{-- Article Specific Meta Tags --}}
@if($type === 'article')
    @if($publishedTime)
        <meta property="article:published_time" content="{{ $publishedTime }}">
    @endif
    @if($modifiedTime)
        <meta property="article:modified_time" content="{{ $modifiedTime }}">
    @endif
    <meta property="article:author" content="{{ $author }}">
@endif
{{-- Twitter Card Meta Tags --}}
<meta name="twitter:card" content="{{ $twitterCard }}">
<meta name="twitter:url" content="{{ $canonicalUrl }}">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $image }}">
@if(config('services.twitter.username'))
    <meta name="twitter:site" content="@{{ config('services.twitter.username') }}">
    <meta name="twitter:creator" content="@{{ config('services.twitter.username') }}">
@endif
{{-- Additional SEO Meta Tags --}}
<meta name="language" content="vi">
<meta name="revisit-after" content="7 days">
<meta name="rating" content="general">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
{{-- Mobile Optimization --}}
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
<meta name="theme-color" content="#ffffff">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="{{ setting('site_name') }}">
{{-- Favicon & Icons --}}
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
<link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">
{{-- Site Verification --}}
@if(config('services.google.verification'))
    <meta name="google-site-verification" content="{{ config('services.google.verification') }}">
@endif
@if(config('services.bing.verification'))
    <meta name="msvalidate.01" content="{{ config('services.bing.verification') }}">
@endif
@if(config('services.pinterest.verification'))
    <meta name="p:domain_verify" content="{{ config('services.pinterest.verification') }}">
@endif
{{-- Structured Data (JSON-LD) --}}
<script type="application/ld+json">
{!! json_encode($getStructuredData(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
{{-- Alternate Languages (if multilingual) --}}
@if(config('app.locales'))
    @foreach(config('app.locales') as $locale => $name)
        @if($locale !== app()->getLocale())
            <link rel="alternate" hreflang="{{ $locale }}" href="{{ url()->current() }}?lang={{ $locale }}">
        @endif
    @endforeach
@endif

{{-- RSS Feed (optional) --}}
@if(Route::has('feed'))
    <link rel="alternate" type="application/rss+xml" title="{{ setting('site_name') }} RSS Feed" href="{{ route('feed') }}">
@endif