<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    public function index(Request $request)
    {
        $menuId = $request->get('menu_id');
        if (!$menuId) {
            return redirect()->route('admin.menus.index')->with('error', 'Menu ID is required');
        }

        $menu = Menu::findOrFail($menuId);
        $menuItems = $menu->items()->with('children')->orderBy('order')->get();

        return view('admin.menu_items.index', compact('menu', 'menuItems'));
    }

    public function create(Request $request)
    {
        $menuId = $request->get('menu_id');
        $menu = Menu::findOrFail($menuId);

        $parentItems = $menu->items()->whereNull('parent_id')->orderBy('order')->get();

        return view('admin.menu_items.create', compact('menu', 'parentItems'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'parent_id' => 'nullable|exists:menu_items,id',
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'target' => 'nullable|in:_self,_blank',
            'icon' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
        ]);

        MenuItem::create($validated);

        return redirect()->route('admin.menu-items.index', ['menu_id' => $validated['menu_id']])->with('success', 'Menu item created.');
    }

    public function edit(MenuItem $menuItem)
    {
        $menu = $menuItem->menu;
        $parentItems = $menu->items()->whereNull('parent_id')->where('id', '!=', $menuItem->id)->orderBy('order')->get();

        return view('admin.menu_items.edit', compact('menuItem', 'menu', 'parentItems'));
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:menu_items,id',
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'target' => 'nullable|in:_self,_blank',
            'icon' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
        ]);

        $menuItem->update($validated);

        return redirect()->route('admin.menu-items.index', ['menu_id' => $menuItem->menu_id])->with('success', 'Menu item updated.');
    }

    public function destroy(MenuItem $menuItem)
    {
        $menuId = $menuItem->menu_id;
        $menuItem->delete();

        return redirect()->route('admin.menu-items.index', ['menu_id' => $menuId])->with('success', 'Menu item deleted.');
    }
}