<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FeedbackSecureController extends Controller
{
    public function index()
    {
        return view('feedback');
    }
    public function store(Request $request)
    {
        $name = Auth::check() ? Auth::user()->name : 'Guest';

        $email = trim($request->email);
        $message = trim($request->message);

        // Kiểm tra email hợp lệ
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return back()->withInput()->with('error', 'Email không hợp lệ');
        }

        // Tách domain từ email
        $domain = substr(strrchr($email, "@"), 1);
        $domain = strtolower(trim($domain));

        if (!$domain) {
            return back()->withInput()->with('error', 'Domain không hợp lệ');
        }

        // Chỉ cho phép ký tự hợp lệ trong domain
        if (!preg_match('/^[a-zA-Z0-9.-]+$/', $domain)) {
            return back()->withInput()->with('error', 'Domain không hợp lệ');
        }

        if (!checkdnsrr($domain, 'MX') && !checkdnsrr($domain, 'A')) {
            return back()->withInput()->with('error', 'Domain không tồn tại hoặc không hỗ trợ nhận mail');
        }

        $imageName = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // Đổi tên file an toàn hơn
            $imageName = uniqid('fb_', true) . '.' . $file->extension();
            $file->move(public_path('uploads/feedback'), $imageName);
        }

        Feedback::create([
            'name' => $name,
            'email' => $email,
            'message' => $message,
            'image' => $imageName
        ]);

        return back()->with('success', 'Gửi feedback thành công!');
    }
}