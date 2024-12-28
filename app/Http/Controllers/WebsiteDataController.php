<?php

namespace App\Http\Controllers;

use App\Models\SecondarySlider;
use App\Models\Slider;
use App\Models\WebsiteData;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Models\User;

class WebsiteDataController extends Controller
{

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Optional: Send verification email if enabled
        if (method_exists($user, 'sendEmailVerificationNotification')) {
            // $user->sendEmailVerificationNotification();
        }

        return response()->json(['message' => 'User registered successfully'], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = $request->user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
        ]);
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Reset link sent'])
            : response()->json(['message' => 'Error sending reset link'], 500);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Password reset successful'])
            : response()->json(['message' => 'Password reset failed'], 500);
    }

    public function verifyEmail(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'Unauthorized action.');
        }

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return response()->json(['message' => 'Email verified successfully']);
    }

    public function resendVerificationEmail(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification email resent']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index($lang = 'ar')
    {
        $lang = $lang == 'en' ? 'en' : 'ar';

        $cols = [
            'rooms_count',
            'phone_num1',
            'phone_num2',
            'email',
            'address_'.$lang . ' as address',
            'latitude',
            'longitude',
            'instagram_link',
        ];

        return WebsiteData::select($cols)->first();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(WebsiteData $websiteData)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WebsiteData $websiteData)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WebsiteData $websiteData)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WebsiteData $websiteData)
    {
        //
    }

    public function slider()
    {
        return Slider::orderBy('order')
            ->select('id', 'image')
            ->get()
            ->map(function ($item) {
                $item->image = asset('storage/' . $item->image);
                return $item;
            });
    }  

    public function secondarySlider()
    {
        return SecondarySlider::orderBy('order')
            ->select('id', 'image')
            ->get()
            ->map(function ($item) {
                $item->image = asset('storage/' . $item->image);
                return $item;
            });
    } 
}
