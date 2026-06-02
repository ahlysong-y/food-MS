<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // មុខងារសម្រាប់ចុះឈ្មោះបុគ្គលិកថ្មី (Register)
    public function register(Request $request)
    {
        // ១. ត្រួតពិនិត្យទិន្នន័យដែលបានវាយបញ្ចូល
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|string',
        ]);

        // ត្រូវប្រាកដថាមានសាខាទី១ នៅក្នុងប្រព័ន្ធ (ការពារកុំអោយ Error)
        $branch = \App\Models\Branch::firstOrCreate(
            ['id' => 1],
            ['name' => 'សាខាទី១ កណ្តាល']
        );

        // ២. បង្កើតគណនីថ្មីចូលក្នុង Database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'branch_id' => $branch->id,
        ]);

        // ៣. បង្កើត Token សម្រាប់ Login ចូលស្វ័យប្រវត្តិ
        $token = $user->createToken('food_pro_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'ចុះឈ្មោះគណនីបានជោគជ័យ!',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'branch_id' => $user->branch_id
            ]
        ], 201);
    }
    // មុខងារសម្រាប់ Login
    public function login(Request $request)
    {
        // ១. ពិនិត្យមើលទិន្នន័យ
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // ២. ស្វែងរកគណនីក្នុង Database
        $user = User::with('branch')->where('email', $request->email)->first();

        // ៣. ផ្ទៀងផ្ទាត់ពាក្យសម្ងាត់
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'អុីមែល ឬពាក្យសម្ងាត់មិនត្រឹមត្រូវទេ!'
            ], 401);
        }

        // ៤. បង្កើត Token សុវត្ថិភាព (Sanctum)
        $token = $user->createToken('food_pro_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'ចូលប្រព័ន្ធបានជោគជ័យ!',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'branch_id' => $user->branch_id
            ]
        ], 200);
    }

    // មុខងារសម្រាប់ Logout
    public function logout(Request $request)
    {
        // លុប Token ចោលដើម្បីកុំអោយប្រើបានទៀត
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'ចាកចេញពីប្រព័ន្ធបានជោគជ័យ!'
        ], 200);
    }
}
