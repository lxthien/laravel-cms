
@forelse($categories as $category)
<tr>
    <td class="px-6 py-4 whitespace-nowrap">{{ $category->id }}</td>
    <td class="px-6 py-4">
        {!! str_repeat('-', $level * 6) !!}
        {{ $category->name }}
    </td>
    <td class="px-6 py-4">{{ $category->slug }}</td>
    <td class="px-6 py-4">{{ $category->parent->name ?? '-' }}</td>
    <td class="px-6 py-4">{{ $category->order }}</td>
    <td class="px-6 py-4">
        <span class="px-2 py-1 text-xs rounded {{ $category->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
            {{ $category->status ? 'Hoạt động' : 'Ẩn' }}
        </span>
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