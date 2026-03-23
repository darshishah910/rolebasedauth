<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Services\ProfileService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     * ✅ Show Profile Page
     */
    public function index(Request $request)
    {
        $user = $request->user();

        return Inertia::render('Profile', [
            'user' => $this->profileService->getProfile($user)
        ]);
    }

    /**
     * ✅ Update Profile
     */
    public function update(ProfileRequest $request)
    {
        $this->profileService->updateProfile(
            $request->user(),
            $request->validated()
        );

        return back()->with('success', 'Profile updated successfully');
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
            return back()->withErrors([
                'current_password' => $result['message']
            ]);
        }

        return back()->with('success', $result['message']);
    }
}