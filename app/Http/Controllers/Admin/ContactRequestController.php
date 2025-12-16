<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactRequest;

use Illuminate\Http\Request;

class ContactRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactRequest::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $contacts = $query->latest()->paginate(20);
        return view('admin.contacts.index', compact('contacts'));
    }

    public function show(ContactRequest $contact)
    {
        return view('admin.contacts.show', compact('contact'));
    }

    public function destroy(ContactRequest $contact)
    {
        $contact->delete();
        return back()->with('success', 'Đã xoá yêu cầu liên hệ!');
    }
}
