<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\VerificationCodeMail;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Register 
    public function register(Request $request)
    {
        // Validation
        $validator = \Validator::make($request->all(), [
            "first_name" => "required|string",
            "last_name" => "required|string",
            "email" => "required|string|email|unique:users",
            "password" => "required|confirmed",
        ]);

        // If validation fails, return an error response
        if ($validator->fails()) {
            return apiErrorResponse('Validation error', 422, $validator->errors());
        }

        try {
            // Generate a unique user ID
            $userId = generateUniqueUserId();

            // Generate a random verification code
            $verificationCode = rand(100000, 999999);

            // Save user to the database
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'username' => $request->username,
                "password" => bcrypt($request->password),
                "phone" => $request->phone,
                "address" => $request->address,
                "state" => $request->state,
                "country" => $request->country,
                "user_id" => $userId,
                "email_verification_code" => $verificationCode,
                "is_verified" => false 
            ]);

            // Send the verification email
            Mail::to($user->email)->send(new VerificationCodeMail($verificationCode));
            
            mail($user->email, 'Email Verification', "Verify Your email with the following code", 
                'from: noreply@user.com');

            return apiResponse([], 'User registered successfully. Please check your email for the verification code.');

        } catch (\Exception $e) {
            // Return a server error response
            return apiErrorResponse('Registration failed. Please try again.', 500, $e->getMessage());
        }
    }

    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|exists:api_userdata,email',
            'verification_code' => 'required|string'
        ]);

        $user = User::where('email', $request->email)
                                        ->where('email_verification_code', $request->verification_code)
                                        ->first();

        if (!$user) {
            return response()->json([
                "status" => false,
                "message" => "Invalid verification code."
            ], 400);
        }

        // Mark user as verified
        $user->is_verified = true;
        $user->email_verification_code = null;
        $user->email_verified_at = now();
        $user->is_active = 1;
        $user->save();

        return response()->json([
            "status" => true,
            "message" => "Email verified successfully!"
        ]);
    }

    // Login API
    public function login(Request $request)
    {
        // Validation
        $request->validate([
            "username" => "required",
            "password" => "required"
        ]);

        // Attempt to authenticate the user
        $token = auth()->attempt([
            "username" => $request->username,
            "password" => $request->password
        ]);

        // If authentication fails, return an error
        if (!$token) {
            return response()->json([
                "status" => false,
                "message" => "Invalid login details"
            ]);
        }

        // Get the authenticated user
        $user = auth()->user();

        $user->update([
            'last_login' => now()
        ]);

        return response()->json([
            "status" => true,
            "message" => "User logged in",
            "token" => $token,
            "user" => new UserResource($user),
            "expires_in" => auth()->factory()->getTTL() * 60
        ]);
    }

    // Refresh Token API 
    public function refreshToken(){

        $token = auth()->refresh();

        return response()->json([
            "status" => true,
            "message" => "Refresh token",
            "token" => $token,
            "expires_in" => auth()->factory()->getTTL() * 60
        ]);
    }

    // Logout API 
    public function logout(){
        
        auth()->logout();

        return response()->json([
            "status" => true,
            "message" => "User logged out"
        ]);
    }

    public function getUser($user_id)
    {
        $user = getUserByUserId($user_id);
    
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user;
        }
    
        return new UserResource($user);
    } 


}
