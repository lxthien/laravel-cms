<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Show all settings grouped by category
     */
    public function index()
    {
        // Lấy settings theo nhóm, ví dụ nhóm "general" và "seo"
        $groups = Setting::select('group')->distinct()->pluck('group');

        $settings = [];
        foreach ($groups as $group) {
            $settings[$group] = Setting::where('group', $group)->orderBy('key')->get();
        }

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Show form edit for a group of settings
     */
    public function edit($group)
    {
        $settings = Setting::where('group', $group)->orderBy('key')->get();

        if ($settings->isEmpty()) {
            return redirect()->route('admin.settings.index')->with('error', 'Nhóm cấu hình không tồn tại.');
        }

        return view('admin.settings.edit', compact('settings', 'group'));
    }

    /**
     * Update settings in group
     */
    public function update(Request $request, $group)
    {
        $settings = Setting::where('group', $group)->get();

        foreach ($settings as $setting) {
            $key = $setting->key;

            if ($setting->type == 'image' && $request->hasFile($key)) {
                $path = $request->file($key)->store('settings', 'public');
                
                // Optionally: Xóa ảnh cũ
                if ($setting->value && \Storage::disk('public')->exists($setting->value)) {
                    \Storage::disk('public')->delete($setting->value);
                }
                $setting->value = $path;
                $setting->save();
                continue;
            }

            if ($request->has($key)) {
                $value = $request->input($key);

                // Với boolean checkbox, nếu không checked thì không có trong request
                if ($setting->type == 'boolean') {
                    $value = $value ? 1 : 0;
                }

                $setting->value = $value;
                $setting->save();
            }
        }

        return redirect()->route('admin.settings.edit', $group)->with('success', 'Cập nhật cấu hình thành công.');
    }
}