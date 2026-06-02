<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // មុខងារសម្រាប់ ចូលប្រព័ន្ធ (Login)
    public function login(Request $request)
    {
        // ត្រួតពិនិត្យទិន្នន័យដែលបានបញ្ជូនមក
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // ស្វែងរកគណនីក្នុង Database
        $user = User::with('branch')->where('email', $request->email)->first();

        // ផ្ទៀងផ្ទាត់លេខសម្ងាត់
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'អ៊ីមែល ឬលេខសម្ងាត់មិនត្រឹមត្រូវ!'
            ], 401);
        }

        // បង្កើត Token (សោសុវត្ថិភាព)
        $token = $user->createToken('food_ms_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'ចូលប្រព័ន្ធបានជោគជ័យ!',
            'user' => $user, // ទិន្នន័យនេះនឹងត្រូវបញ្ជូនទៅ Frontend ដើម្បីបង្ហាញឈ្មោះ និង Role លើ Sidebar
            'token' => $token
        ], 200);
    }

    // មុខងារសម្រាប់ ចាកចេញ (Logout)
    public function logout(Request $request)
    {
        // លុប Token ចោល
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'ចាកចេញពីប្រព័ន្ធបានជោគជ័យ!'
        ], 200);
    }
}
