<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class SeoMeta extends Component
{
    public string $title;
    public string $description;
    public ?string $keywords;
    public string $canonicalUrl;
    public string $image;
    public string $robots;
    public string $type;
    public ?string $author;
    public string $twitterCard;
    public ?string $publishedTime;
    public ?string $modifiedTime;

    /**
     * Create a new component instance.
     */
    public function __construct(
        ?string $title = null,
        ?string $description = null,
        ?string $keywords = null,
        ?string $canonicalUrl = null,
        ?string $image = null,
        string $robots = 'index, follow',
        string $type = 'website',
        ?string $author = null,
        string $twitterCard = 'summary_large_image',
        ?string $publishedTime = null,
        ?string $modifiedTime = null
    ) {
        $this->title = $this->sanitizeTitle($title);
        $this->description = $this->sanitizeDescription($description);
        $this->keywords = $keywords;
        $this->canonicalUrl = $canonicalUrl ?: url()->current();
        $this->image = $this->getImage($image);
        $this->robots = $robots;
        $this->type = $type;
        $this->author = $author ?: config('app.name');
        $this->twitterCard = $twitterCard;
        $this->publishedTime = $publishedTime;
        $this->modifiedTime = $modifiedTime;
    }

    /**
     * Sanitize and validate title
     * Giới hạn 60 ký tự cho SEO tối ưu
     */
    private function sanitizeTitle(?string $title): string
    {
        $title = $title ?: setting('site_name');

        // Remove HTML tags
        $title = strip_tags($title);

        // Giới hạn 60 ký tự cho Google Search
        if (mb_strlen($title) > 60) {
            $title = mb_substr($title, 0, 57) . '...';
        }

        return trim($title);
    }

    /**
     * Sanitize and validate description
     * Giới hạn 160 ký tự cho SEO tối ưu
     */
    private function sanitizeDescription(?string $description): string
    {
        $description = $description ?: setting('page_description');

        // Remove HTML tags
        $description = strip_tags($description);

        // Giới hạn 160 ký tự cho Google Search
        if (mb_strlen($description) > 160) {
            $description = mb_substr($description, 0, 157) . '...';
        }

        return trim($description);
    }

    /**
     * Get OG image with fallback
     * Validate URL và kiểm tra file tồn tại
     */
    private function getImage(?string $image): string
    {
        // Nếu là URL đầy đủ và hợp lệ
        if ($image && filter_var($image, FILTER_VALIDATE_URL)) {
            return $image;
        }

        // Nếu là đường dẫn tương đối từ storage
        if ($image && file_exists(public_path('storage/' . $image))) {
            return asset('storage/' . $image);
        }

        // Nếu là đường dẫn tương đối từ public
        if ($image && file_exists(public_path($image))) {
            return asset($image);
        }

        // Fallback to default OG image
        return asset('images/default-og.jpg');
    }

    /**
     * Check if page should be indexed by search engines
     */
    public function shouldIndex(): bool
    {
        return str_contains(strtolower($this->robots), 'index');
    }

    /**
     * Check if page should be followed by search engines
     */
    public function shouldFollow(): bool
    {
        return str_contains(strtolower($this->robots), 'follow');
    }

    /**
     * Get full title with site name
     */
    public function getFullTitle(): string
    {
        $siteName = config('app.name');

        if ($this->title === $siteName) {
            return $this->title;
        }

        return $this->title . ' - ' . $siteName;
    }

    /**
     * Get structured data for JSON-LD
     * Schema.org markup cho SEO
     */
    public function getStructuredData(): array
    {
        $data = [
            '@context' => 'https://schema.org',
            '@type' => $this->getSchemaType(),
            'name' => $this->title,
            'description' => $this->description,
            'url' => $this->canonicalUrl,
            'image' => $this->image,
        ];

        // Add Article specific fields
        if ($this->type === 'article') {
            $data['headline'] = $this->title;
            $data['author'] = [
                '@type' => 'Person',
                'name' => $this->author,
            ];

            if ($this->publishedTime) {
                $data['datePublished'] = $this->publishedTime;
            }

            if ($this->modifiedTime) {
                $data['dateModified'] = $this->modifiedTime;
            }

            // Add publisher info
            $data['publisher'] = [
                '@type' => 'Organization',
                'name' => config('app.name'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('images/logo.png'),
                ],
            ];
        }

        return $data;
    }

    /**
     * Get Schema.org type based on OG type
     */
    private function getSchemaType(): string
    {
        return match ($this->type) {
            'article' => 'Article',
            'product' => 'Product',
            'video' => 'VideoObject',
            'book' => 'Book',
            default => 'WebPage',
        };
    }

    /**
     * Get the view that represents the component
     */
    public function render(): View
    {
        return view('components.seo-meta');
    }
}