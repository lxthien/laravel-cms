<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactRequest;

class ContactRequestController extends Controller
{
    public function index()
    {
        $contacts = ContactRequest::latest()->paginate(20);
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
