<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ForgotPasswordController extends Controller
{
    /**
     * Handle the incoming request to send a password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        if ($status == Password::RESET_LINK_SENT) {
            // Send notification
            Notification::route('mail', $request->email)->notify(new ResetPasswordNotification($status));

            Log::info('Password reset email sent to ' . $request->email);
            return response()->json(['status' => 'success']);
        } else {
            Log::error('Failed to send password reset email to ' . $request->email);
            return response()->json(['status' => 'error', 'message' => Lang::get($status)]);
        }
    }
}
