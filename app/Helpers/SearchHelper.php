<?php

if (!function_exists('highlightSearchTerm')) {
    function highlightSearchTerm($text, $search)
    {
        if (empty($search)) {
            return $text;
        }
        
        return preg_replace(
            '/(' . preg_quote($search, '/') . ')/i',
            '<mark class="bg-yellow-200 px-1">$1</mark>',
            $text
        );
    }
}