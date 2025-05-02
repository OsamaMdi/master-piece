<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'    => $user ? 'nullable' : 'required|string|max:255',
            'email'   => $user ? 'nullable' : 'required|email|max:255',
            'message' => 'required|string|max:2000',
        ]);

        $name = $user ? $user->name : $request->input('name');
        $email = $user ? $user->email : $request->input('email');
        $messageText = $request->input('message');

      
        $adminEmail = env('MAIL_TO_ADDRESS', 'admin@example.com');

        Mail::raw("New message received:\n\nName: $name\nEmail: $email\n\n$messageText", function ($message) use ($adminEmail, $email, $name) {
            $message->to($adminEmail)
                    ->from($email ?: 'noreply@rentify.com', $name ?: 'Visitor')
                    ->subject('New Contact Form Submission');
        });

        return back()->with('success', 'Your message has been sent successfully!');
    }
}
