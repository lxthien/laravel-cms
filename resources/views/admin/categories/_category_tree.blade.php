
@forelse($categories as $category)
<tr>
    <td class="px-6 py-4 whitespace-nowrap">{{ $category->id }}</td>
    <td class="px-6 py-4">
        {!! str_repeat('-', $level * 6) !!}
        {{ $category->name }}
    </td>
    <td class="px-6 py-4">{{ $category->slug }}</td>
    <td class="px-6 py-4">{{ $category->parent->name ?? '-' }}</td>
    {{-- Editable Order Field --}}
    <td class="border p-2">
        <input 
            type="number" 
            class="order-input w-20 border border-gray-300 rounded px-2 py-1 text-center focus:outline-none focus:ring-2 focus:ring-blue-500"
            value="{{ $category->order ?? 0 }}"
            data-category-id="{{ $category->id }}"
            data-old-value="{{ $category->order ?? 0 }}"
            min="0"
        >
    </td>
    {{-- Toggle Switch for Status --}}
    <td class="border p-2">
        <label class="relative inline-flex items-center cursor-pointer">
            <input 
                type="checkbox" 
                class="status-toggle sr-only peer" 
                data-category-id="{{ $category->id }}"
                {{ $category->status ? 'checked' : '' }}
            >
            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
            <span class="ms-3 text-sm font-medium text-gray-900">
                {{ $category->status ? 'Hoạt động' : 'Ẩn' }}
            </span>
        </label>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm">
        <div class="flex gap-2">
            @can('category-edit')
            <a href="{{ route('admin.categories.edit', $category) }}" 
                class="text-blue-600 hover:text-blue-900">
                Sửa
            </a>
            @endcan
            
            @can('category-delete')
            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                    onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-900">
                    Xóa
                </button>
            </form>
            @endcan
        </div>
    </td>
</tr>
@if ($category->childrenRecursive->count())
    @include('admin.categories._category_tree', [
        'categories' => $category->childrenRecursive,
        'level' => $level + 1
    ])
@endif
@empty
<tr>
    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
        Chưa có danh mục nào.
    </td>
</tr>
@endforelse