<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            "name" => "required|max:255",
            "username" => "required|max:64|alpha_dash|unique:users",
            "password" => "required",
            "profile_photo" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
        ]);

        $photoPath = null;
        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $user = User::create([
            "name" => $request->name,
            "username" => $request->username,
            "password" => bcrypt($request->password),
            "profile_photo" => $photoPath,
            "role" => $request->role ?? "member", // Jika `role` tidak dikirim, default "member"
        ]);
        

        $token = $user->createToken("sanctum_token")->plainTextToken;

        return response()->json([
            "message" => "Account registered successfully",
            "token" => $token,
            "profile_photo_url" => $photoPath ? asset("storage/" . $photoPath) : null
        ], 200);
    }

    public function login(Request $request)
    {
        $request->validate([
            "username" => "required|max:64|alpha_dash",
            "password" => "required",
        ]);

        $user = User::where("username", $request->username)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                "message" => "Invalid username or password.",
            ], 401);
        }

        $token = $user->createToken("sanctum_token")->plainTextToken;

        return response()->json([
            "message" => "Login successful.",
            "token" => $token,
            "profile_photo_url" => $user->profile_photo ? asset("storage/" . $user->profile_photo) : null
        ], 200);
    }

    public function updateUser(Request $request)
    {
        $user = auth('sanctum')->user();
    
        if (!$user) {
            return response()->json(["message" => "Unauthorized."], 401)->header('Access-Control-Allow-Origin', 'http://localhost:5173');
        }
    
        $request->validate([
            "name" => "sometimes|required|max:255",
            "username" => "sometimes|required|max:64|alpha_dash|unique:users,username," . $user->id,
            "password" => "sometimes|required",
            "profile_photo" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
        ]);
    
        if ($request->hasFile('profile_photo')) {
            // Hapus foto lama jika ada
            if ($user->profile_photo) {
                Storage::disk("public")->delete($user->profile_photo);
            }
    
            // Simpan foto profil baru
            $photoPath = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->profile_photo = $photoPath;
        }
    
        if ($request->name) {
            $user->name = $request->name;
        }
        if ($request->username) {
            $user->username = $request->username;
        }
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
    
        $user->save();
    
        return response()->json([
            "message" => "User updated successfully.",
            "profile_photo_url" => $user->profile_photo ? asset("storage/" . $user->profile_photo) : null
        ], 200)->header('Access-Control-Allow-Origin', 'http://localhost:5173');
    }
    
    


    public function deleteUser()
    {
        $user = auth("sanctum")->user();
    
        if (!$user) {
            return response()->json(["message" => "Unauthorized."], 401);
        }
    
        if ($user->profile_photo) {
            Storage::disk("public")->delete($user->profile_photo);
        }
    
        User::where("id", $user->id)->delete();
    
        return response()->json(["message" => "User deleted successfully."], 200);
    }

    public function check()
{
    $user = auth("sanctum")->user();
    
    if (!$user) {
        return response()->json(["message" => "Unauthorized."], 401);
    }

    return response()->json([
        "message" => "User authenticated.",
        "user" => [
            "id" => $user->id,
            "name" => $user->name,
            "username" => $user->username,
            "profile_photo_url" => $user->profile_photo ? asset("storage/" . $user->profile_photo) : null,
            "role" => $user->role   
        ]
    ], 200);
}


}
