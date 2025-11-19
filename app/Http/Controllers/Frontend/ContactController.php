<?php

// app/Http/Controllers/Frontend/ContactController.php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactRequest; // Tạo model/migration riêng cho contact
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function showForm()
    {
        // Lấy thông tin liên hệ từ bảng settings
        return view('frontend.contacts.contact');
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|max:100',
            'email'   => 'nullable|email',
            'phone'   => 'required|max:50',
            'message' => 'required|max:2000',
        ]);

        // Lưu contact vào DB
        ContactRequest::create($validated);

        // (tùy chọn) Gửi email cho admin tại đây

        return back()->with('success', 'Cảm ơn bạn đã liên hệ, chúng tôi sẽ phản hồi sớm nhất!');
    }
}