<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = Menu::orderBy('name')->paginate(20);
        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.menus.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255|unique:menus,location',
        ]);

        $menu = Menu::create($validated);

        return redirect()->route('admin.menus.edit', $menu)->with('success', 'Menu đã được tạo. Bây giờ bạn có thể thêm các mục vào menu.');
    }

    public function edit(Menu $menu)
    {
        $pages = \App\Models\Page::published()->latest()->take(10)->get();
        $posts = \App\Models\Post::published()->latest()->take(10)->get();
        $categories = \App\Models\Category::active()->latest()->take(10)->get();

        $menuItems = $menu->rootItems()->with('children.children')->get();

        return view('admin.menus.edit', compact('menu', 'pages', 'posts', 'categories', 'menuItems'));
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255|unique:menus,location,' . $menu->id,
        ]);

        $menu->update($validated);

        if ($request->ajax()) {
            return response()->json(['message' => 'Lưu thông tin menu thành công!']);
        }

        return redirect()->route('admin.menus.index')->with('success', 'Cập nhật menu thành công.');
    }

    /**
     * Update menu items structure (nesting and order)
     */
    public function updateStructure(Request $request, Menu $menu)
    {
        $items = $request->input('items', []);

        // Save all items and collect their real database IDs
        $processedIds = $this->saveItemsRecursive($items, $menu->id);

        // Delete items that belong to this menu but weren't in the processed list
        $menu->items()->whereNotIn('id', $processedIds)->delete();

        return response()->json(['message' => 'Cập nhật cấu trúc menu thành công!']);
    }

    private function saveItemsRecursive($items, $menuId, $parentId = null)
    {
        $allProcessedIds = [];

        foreach ($items as $index => $itemData) {
            $id = (isset($itemData['id']) && is_numeric($itemData['id'])) ? $itemData['id'] : null;

            $item = \App\Models\MenuItem::updateOrCreate(
                ['id' => $id],
                [
                    'menu_id' => $menuId,
                    'parent_id' => $parentId,
                    'title' => $itemData['title'],
                    'url' => empty($itemData['url']) ? null : $itemData['url'],
                    'target' => $itemData['target'] ?? '_self',
                    'icon' => $itemData['icon'] ?? null,
                    'css_class' => $itemData['css_class'] ?? null,
                    'model_type' => $itemData['model_type'] ?? null,
                    'model_id' => $itemData['model_id'] ?? null,
                    'order' => $index,
                ]
            );

            $allProcessedIds[] = $item->id;

            if (!empty($itemData['children'])) {
                $childIds = $this->saveItemsRecursive($itemData['children'], $menuId, $item->id);
                $allProcessedIds = array_merge($allProcessedIds, $childIds);
            }
        }

        return $allProcessedIds;
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return redirect()->route('admin.menus.index')->with('success', 'Menu deleted.');
    }
}