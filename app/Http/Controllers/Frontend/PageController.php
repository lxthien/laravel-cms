<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display the specified page.
     *
     * @param  string  $slug
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        // Find the page by slug and ensure it's published
        $page = Page::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Build breadcrumbs
        $breadcrumbs = $this->buildBreadcrumbs($page);

        return view('frontend.pages.show', compact('page', 'breadcrumbs'));
    }

    /**
     * Recursively build breadcrumbs for the page.
     *
     * @param  \App\Models\Page  $page
     * @return array
     */
    protected function buildBreadcrumbs(Page $page)
    {
        $breadcrumbs = [];

        // Start with current page
        $current = $page;

        while ($current) {
            array_unshift($breadcrumbs, [
                'title' => $current->title,
                'url' => url('/' . $current->slug),
            ]);

            // Move to parent
            $current = $current->parent;
        }

        // Add Home at the beginning
        array_unshift($breadcrumbs, [
            'title' => 'Trang chủ',
            'url' => route('home'),
        ]);

        return $breadcrumbs;
    }
}
