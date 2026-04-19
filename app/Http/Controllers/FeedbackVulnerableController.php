<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;

class FeedbackVulnerableController extends Controller
{
    

   public function store(Request $request)
{
    $name = Auth::check() ? Auth::user()->name : 'Guest';
    $email = $request->email;
    $message = $request->message;
    
    # ---- Here -----
    $domain = substr(strrchr($email, "@"), 1);

    if (!$domain) {
        return back()->with('error', 'Domain không hợp lệ');
    }

    // Lệnh nslookup
    $command = "nslookup -type=MX " . $domain;
    $output = shell_exec($command);

    // Check domain 
    if (empty($output) || stripos($output, 'mail exchanger') === false) {
    return back()->with('error', 'Email domain không hỗ trợ nhận mail');
    }
 # ---- Here -----
    $imageName = null;

    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $imageName = $file->getClientOriginalName();
        $file->move(public_path('uploads/feedback'), $imageName);
    }

    Feedback::create([
        'name' => $name,
        'email' => $email,
        'message' => $message,
        'image' => $imageName
    ]);

   return back() ->with('success', 'Gửi feedback thành công!');
}
}