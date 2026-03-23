<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserPermission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    // ✅ REQUIRED (THIS FIXES YOUR ERROR)
    public function index()
    {
        return response()->json([
            'roles' => ['admin', 'manager', 'user'],
            'permissions' => [
                'view_product',
                'create_product',
                'edit_product',
                'delete_product'
            ]
        ]);
    }

    public function assignUserPermissions(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'permissions' => 'required|array'
        ]);

        UserPermission::where('user_id', $request->user_id)->delete();

        foreach ($request->permissions as $perm) {
            UserPermission::create([
                'user_id' => $request->user_id,
                'permission' => $perm
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Permissions saved'
        ]);
    }

    public function allUsersWithPermissions()
    {
        $users = User::with('permissions')->get();

        return response()->json(
            $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'role' => $user->role,
                    'permissions' => $user->permissions->pluck('permission'),
                ];
            })
        );
    }
}