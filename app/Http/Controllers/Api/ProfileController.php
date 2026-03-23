<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Services\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     * ✅ View Profile
     */
    public function show(Request $request)
{
    $user = $request->user();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthenticated'
        ], 401);
    }

    return response()->json([
        'success' => true,
        'data' => $this->profileService->getProfile($user)
    ]);
}

    /**
     * ✅ Update Profile
     */
    public function update(ProfileRequest $request)
    {
        $user = $this->profileService->updateProfile(
            $request->user(),
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $user
        ]);
    }

    /**
     * ✅ Change Password
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $result = $this->profileService->changePassword(
            $request->user(),
            $request->validated()
        );

        if (!$result['status']) {
            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => $result['message']
        ]);
    }
}