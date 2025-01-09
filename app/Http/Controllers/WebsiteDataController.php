<?php

namespace App\Http\Controllers;

use App\Mail\VerificationCodeMail;
use App\Models\SecondarySlider;
use App\Models\Slider;
use App\Models\WebsiteData;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class WebsiteDataController extends Controller
{

    public function user()
    {
        return Auth::user();
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $verificationCode = Str::random(6); // Generate a random 6-character code

        $user = User::create([
            'name' => $request->name,
            'last_name' => $request->name,
            'title' => $request->name,
            'country' => $request->name,
            'state' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'verification_code' => $verificationCode,
            'verification_code_expires_at' => Carbon::now()->addMinutes(10), // Code expires in 10 minutes
        ]);

        // Send the verification code via email
        Mail::to($user->email)->send(new VerificationCodeMail($verificationCode));

        return response()->json(['message' => 'User registered successfully. Verification code sent to email.'], 201);

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

    public function resendVerificationCode(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $verificationCode = Str::random(6);
        $user->verification_code = $verificationCode;
        $user->verification_code_expires_at = Carbon::now()->addMinutes(10);
        $user->save();

        Mail::to($user->email)->send(new VerificationCodeMail($verificationCode));

        return response()->json(['message' => 'Verification code resent']);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'verification_code' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($user->verification_code !== $request->verification_code) {
            return response()->json(['message' => 'Invalid verification code'], 400);
        }

        if (Carbon::now()->greaterThan($user->verification_code_expires_at)) {
            return response()->json(['message' => 'Verification code expired'], 400);
        }

        $user->markEmailAsVerified();
        $user->verification_code = null;
        $user->verification_code_expires_at = null;
        $user->save();

        return response()->json(['message' => 'Email verified successfully']);
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
            'image_1',
            'image_2',
            'image_3',
            'image_4',
        ];

        $websiteData = WebsiteData::select($cols)->first();

        if ($websiteData) {
            $websiteData->image_1 = asset('storage/' . $websiteData->image_1);
            $websiteData->image_2 = asset('storage/' . $websiteData->image_2);
            $websiteData->image_3 = asset('storage/' . $websiteData->image_3);
            $websiteData->image_4 = asset('storage/' . $websiteData->image_4);
        }

        return $websiteData;
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
