<?php

namespace App\Http\Controllers;

use App\Models\{Post, Category, Page};
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate sitemap index
     */
    public function index()
    {
        $sitemaps = [
            ['loc' => route('sitemap.posts'), 'lastmod' => Post::published()->max('updated_at')],
            ['loc' => route('sitemap.categories'), 'lastmod' => Category::max('updated_at')],
            ['loc' => route('sitemap.pages'), 'lastmod' => Page::published()->max('updated_at')],
        ];

        return response()->view('sitemap.index', compact('sitemaps'))
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Generate posts sitemap
     */
    public function posts()
    {
        $posts = Post::published()
            ->where('index', true) // Chỉ lấy posts có index = true
            ->with('categories')
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->view('sitemap.posts', compact('posts'))
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Generate categories sitemap
     */
    public function categories()
    {
        $categories = Category::where('status', 1)
            ->where('index', true)
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->view('sitemap.categories', compact('categories'))
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Generate pages sitemap
     */
    public function pages()
    {
        $pages = Page::published()
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->view('sitemap.pages', compact('pages'))
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Generate robots.txt
     */
    public function robots()
    {
        $content = view('sitemap.robots')->render();

        return response($content)
            ->header('Content-Type', 'text/plain');
    }
}