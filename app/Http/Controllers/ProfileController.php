<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeNameRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ChangeProfileImageRequest;
use App\Http\Resources\ProfileResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'data' => [
                'success' => null,
                'message' => 'Logged out successfully',
            ],
        ]);
    }

    /**
     * Change password.
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $user = $request->user();

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'data' => [
                    'success' => null,
                    'message' => 'Old password is incorrect',
                ],
            ], 401);
        }

        $user->update(['password' => Hash::make($request->new_password)]);
        $user->tokens()->delete();

        return response()->json([
            'data' => [
                'success' => null,
                'message' => 'Password changed successfully',
            ],
        ]);
    }

    /**
     * Change name.
     */
    public function changeName(ChangeNameRequest $request)
    {
        $user = $request->user();
        $user->update(['name' => $request->name]);

        return response()->json([
            'data' => [
                'success' => new ProfileResource($user),
                'message' => 'Name changed successfully',
            ],
        ]);
    }

    /**
     * Change profile image.
     */    
    public function changeProfileImage(ChangeProfileImageRequest $request)
    {
        $user = $request->user();

        // ✅ Delete old image correctly
        if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
        }

        // Upload new image
        $file = $request->file('profile_image');
        $fileName = time() . '.' . $file->getClientOriginalExtension();

        // Store in storage/app/public/profile_images
        $filePath = $file->storeAs('profile_images', $fileName, 'public');

        // Save RELATIVE path only
        $user->update([
            'profile_image' => $filePath
        ]);

        return response()->json([
            'data' => [
                'success' => new ProfileResource($user),
                'message' => 'Profile image changed successfully',
            ],
        ]);
    }

    /**
     * Get user profile.
     */
    public function show(Request $request)
    {
        return response()->json([
            'data' => [
                'success' => new ProfileResource($request->user()),
                'message' => 'User profile retrieved successfully',
            ],
        ]);
    }
}