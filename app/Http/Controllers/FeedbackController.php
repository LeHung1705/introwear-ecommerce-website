<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function index()
    {
        return view('feedback');
    }

   public function store(Request $request)
{
    $name = Auth::check() ? Auth::user()->name : 'Guest';
    $email = $request->email;
    $message = $request->message;

    # ĐAY
    $command = "echo " . ($email);
    $output = shell_exec($command);

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

   return back()
    ->with('success', 'Gửi feedback thành công!')
    ->with('output', $output);
}
}