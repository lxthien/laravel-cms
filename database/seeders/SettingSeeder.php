<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run()
    {
        // Settings group: SEO
        Setting::updateOrCreate(['key' => 'page_title'], [
            'value' => 'My Laravel CMS',
            'type' => 'text',
            'group' => 'seo',
        ]);
        Setting::updateOrCreate(['key' => 'page_description'], [
            'value' => 'My Laravel CMS',
            'type' => 'textarea',
            'group' => 'seo',
        ]);
        Setting::updateOrCreate(['key' => 'page_keyword'], [
            'value' => 'My Laravel CMS',
            'type' => 'text',
            'group' => 'seo',
        ]);

        // Settings group: general
        Setting::updateOrCreate(['key' => 'site_name'], [
            'value' => 'My Laravel CMS',
            'type' => 'text',
            'group' => 'general',
        ]);
        Setting::updateOrCreate(['key' => 'contact_email'], [
            'value' => 'contact@example.com',
            'type' => 'text',
            'group' => 'general',
        ]);
        Setting::updateOrCreate(['key' => 'contact_phone'], [
            'value' => '0123 456 789',
            'type' => 'text',
            'group' => 'general',
        ]);
        Setting::updateOrCreate(['key' => 'contact_address'], [
            'value' => '123 Đường CMS, Hà Nội, Việt Nam',
            'type' => 'textarea',
            'group' => 'general',
        ]);
        Setting::updateOrCreate(['key' => 'site_logo'], [
            'value' => null,
            'type' => 'image',
            'group' => 'general',
        ]);
        Setting::updateOrCreate(['key' => 'site_favicon'], [
            'value' => null,
            'type' => 'image',
            'group' => 'general',
        ]);

        // Settings group: social
        Setting::updateOrCreate(['key' => 'facebook_url'], [
            'value' => 'https://facebook.com/mycms',
            'type' => 'text',
            'group' => 'social',
        ]);
        Setting::updateOrCreate(['key' => 'youtube_url'], [
            'value' => 'https://youtube.com/mycms',
            'type' => 'text',
            'group' => 'social',
        ]);
        Setting::updateOrCreate(['key' => 'tiktok_url'], [
            'value' => '',
            'type' => 'text',
            'group' => 'social',
        ]);
        Setting::updateOrCreate(['key' => 'linkedin_url'], [
            'value' => '',
            'type' => 'text',
            'group' => 'social',
        ]);
        Setting::updateOrCreate(['key' => 'instagram_url'], [
            'value' => '',
            'type' => 'text',
            'group' => 'social',
        ]);
        Setting::updateOrCreate(['key' => 'twitter_url'], [
            'value' => '',
            'type' => 'text',
            'group' => 'social',
        ]);
    }
}
