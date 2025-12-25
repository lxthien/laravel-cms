<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Show all settings grouped by category
     */
    public function index()
    {
        // Redirect to the first available group
        $firstGroup = Setting::select('group')->distinct()->orderBy('group')->value('group');

        if ($firstGroup) {
            return redirect()->route('admin.settings.edit', $firstGroup);
        }

        // If no settings exist
        return view('admin.settings.index', ['settings' => []]);
    }

    /**
     * Show form edit for a group of settings
     */
    public function edit($group)
    {
        // Get all groups for sidebar
        $groups = Setting::select('group')->distinct()->orderBy('group')->pluck('group');

        $settings = Setting::where('group', $group)->orderBy('key')->get();

        if ($settings->isEmpty()) {
            return redirect()->route('admin.settings.index')->with('error', 'Nhóm cấu hình không tồn tại.');
        }

        return view('admin.settings.edit', compact('settings', 'group', 'groups'));
    }

    /**
     * Update settings in group
     */
    public function update(Request $request, $group)
    {
        $activityLogService = app(ActivityLogService::class);
        $settings = Setting::where('group', $group)->get();

        foreach ($settings as $setting) {
            $key = $setting->key;
            $oldValue = $setting->value;

            if ($setting->type == 'image' && $request->hasFile($key)) {
                $path = $request->file($key)->store('settings', 'public');

                // Optionally: Xóa ảnh cũ
                if ($setting->value && \Storage::disk('public')->exists($setting->value)) {
                    \Storage::disk('public')->delete($setting->value);
                }
                $setting->value = $path;
                $setting->save();
                
                // Log thay đổi cài đặt
                $activityLogService->logSettingChange(
                    "{$group}.{$key}",
                    '[Ảnh cũ]',
                    '[Ảnh mới: ' . $path . ']'
                );
                continue;
            }

            // Special handling for boolean fields: unchecked checkboxes are not sent in request
            if ($setting->type == 'boolean') {
                $value = $request->boolean($key) ? 1 : 0;
                if ($setting->value != $value) {
                    $setting->value = $value;
                    $setting->save();
                    $activityLogService->logSettingChange(
                        "{$group}.{$key}",
                        $oldValue,
                        $value
                    );
                }
                continue;
            }

            if ($request->has($key)) {
                $value = $request->input($key);
                if ($setting->value != $value) {
                    $setting->value = $value;
                    $setting->save();
                    $activityLogService->logSettingChange(
                        "{$group}.{$key}",
                        $oldValue,
                        $value
                    );
                }
            }
        }

        return redirect()->route('admin.settings.edit', $group)->with('success', 'Cập nhật cấu hình thành công.');
    }
}