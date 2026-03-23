<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Services\UserService;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserPermission;
use Spatie\Permission\Models\Role;


class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        // $this->middleware('role:Admin')->only(['index','store','update','destroy']);
    }

    // ✅ Get all users
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized'
        ], 403);
    }

        return response()->json([
            'success' => true,
            'data' => $this->userService->getAllUsers()
        ]);
    }

    // ✅ Create user
    public function store(UserRequest $request)
    {
        $user = $this->userService->createUser($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ]);
    }

    // ✅ Update user
    public function update(UserRequest $request, $id)
    {
        $user = $this->userService->updateUser($id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $user
        ]);
    }

    // ✅ Toggle status
    public function toggleStatus(Request $request,$id)
    {
      
        $user = $this->userService->toggleStatus($id,$request->input('is_active'));

        return response()->json([
            'success' => true,
            'message' => 'Status updated',
            'data' => $user
        ]);
    }

    // ✅ Delete
    public function destroy($id)
    {
        $this->userService->deleteUser($id);

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }

    public function stats()
    {
        return response()->json(
            $this->userService->getStats()
        );
    }

    public function assignRole(Request $request, $id)
{
    $request->validate([
        'role' => 'required|exists:roles,name'
    ]);

    $user = User::findOrFail($id);

    $user->syncRoles([$request->role]);

    return response()->json([
        'message' => 'Role assigned',
        'user' => $user->load('roles')
    ]);
}

public function getUserWithPermissions(Request $request)
{
    $user = $request->user();

    // ✅ Admin = all permissions
    if ($user->role === 'admin') {
        $permissions = [
            'view_product',
            'create_product',
            'edit_product',
            'delete_product'
        ];
    } else {
        $permissions = UserPermission::where('user_id', $user->id)
            ->pluck('permission');
    }

    return response()->json([
        'user' => $user,
        'permissions' => $permissions
    ]);
}
    
}