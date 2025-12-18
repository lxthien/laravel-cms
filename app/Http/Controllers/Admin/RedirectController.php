<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class RedirectController extends Controller
{
    public function index(Request $request)
    {
        $query = Redirect::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('source_url', 'LIKE', "%{$search}%")
                    ->orWhere('destination_url', 'LIKE', "%{$search}%")
                    ->orWhere('note', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('match_type', $request->type);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active === '1');
        }

        $redirects = $query->orderBy('order')
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('admin.redirects.index', compact('redirects'));
    }

    public function create()
    {
        return view('admin.redirects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'source_url' => 'required|string',
            'destination_url' => 'required|string',
            'match_type' => 'required|in:exact,wildcard,regex',
            'status_code' => 'required|in:301,302,307,308',
            'is_active' => 'boolean',
            'order' => 'integer',
            'note' => 'nullable|string',
        ]);

        $this->detectChain($validated['source_url'], $validated['destination_url']);

        Redirect::create($validated);

        return redirect()->route('admin.redirects.index')
            ->with('success', 'Redirect đã được tạo thành công.');
    }

    public function edit(Redirect $redirect)
    {
        return view('admin.redirects.edit', compact('redirect'));
    }

    public function update(Request $request, Redirect $redirect)
    {
        $validated = $request->validate([
            'source_url' => 'required|string',
            'destination_url' => 'required|string',
            'match_type' => 'required|in:exact,wildcard,regex',
            'status_code' => 'required|in:301,302,307,308',
            'is_active' => 'boolean',
            'order' => 'integer',
            'note' => 'nullable|string',
        ]);

        $this->detectChain($validated['source_url'], $validated['destination_url'], $redirect->id);

        $redirect->update($validated);

        return redirect()->route('admin.redirects.index')
            ->with('success', 'Redirect đã được cập nhật.');
    }

    public function destroy(Redirect $redirect)
    {
        $redirect->delete();
        return redirect()->route('admin.redirects.index')
            ->with('success', 'Redirect đã được xóa.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        Redirect::whereIn('id', $ids)->delete();
        return response()->json(['message' => 'Đã xóa các redirect đã chọn.']);
    }

    public function toggleStatus(Redirect $redirect)
    {
        $redirect->is_active = !$redirect->is_active;
        $redirect->save();
        return response()->json(['status' => $redirect->is_active]);
    }

    /**
     * CSV Export
     */
    public function exportCsv()
    {
        $redirects = Redirect::all();
        $filename = "redirects_" . date('Y-m-d') . ".csv";
        $handle = fopen('php://temp', 'w');

        // Header
        fputcsv($handle, ['Source URL', 'Destination URL', 'Match Type', 'Status Code', 'Active', 'Order', 'Note']);

        foreach ($redirects as $r) {
            fputcsv($handle, [
                $r->source_url,
                $r->destination_url,
                $r->match_type,
                $r->status_code,
                $r->is_active ? '1' : '0',
                $r->order,
                $r->note
            ]);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return Response::make($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
        ]);
    }

    /**
     * CSV Import
     */
    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');

        // Skip header
        fgetcsv($handle);

        $count = 0;
        while (($data = fgetcsv($handle)) !== FALSE) {
            if (count($data) >= 4) {
                Redirect::create([
                    'source_url' => $data[0],
                    'destination_url' => $data[1],
                    'match_type' => $data[2],
                    'status_code' => (int) $data[3],
                    'is_active' => isset($data[4]) ? (bool) $data[4] : true,
                    'order' => isset($data[5]) ? (int) $data[5] : 0,
                    'note' => $data[6] ?? null,
                ]);
                $count++;
            }
        }
        fclose($handle);

        return back()->with('success', "Đã import thành công $count redirects.");
    }

    /**
     * Chain Detection Logic
     */
    private function detectChain($source, $destination, $excludeId = null)
    {
        // 1. Simple loop: A -> A
        if ($source === $destination) {
            session()->flash('warning', 'Source và Destination trùng nhau. Redirect này có thể gây lỗi loop.');
            return;
        }

        // 2. Direct chain: A -> B and B -> C
        $existsAsSource = Redirect::where('source_url', $destination)
            ->where('is_active', true)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists();

        if ($existsAsSource) {
            session()->flash('warning', "Cảnh báo: Redirect này tạo ra một chuỗi (Chain). Destination '$destination' hiện đang là Source của một redirect khác.");
        }

        // 3. Reverse chain: D -> A and A -> B
        $existsAsDestination = Redirect::where('destination_url', $source)
            ->where('is_active', true)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists();

        if ($existsAsDestination) {
            session()->flash('warning', "Cảnh báo: Redirect này tạo ra một chuỗi (Chain). Source '$source' hiện đang là Destination của một redirect khác.");
        }
    }
}
