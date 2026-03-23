<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    // ✅ Get roles + permissions
    public function index()
    {
        return response()->json([
            'roles' => [
                ['name' => 'Admin'],
                ['name' => 'Manager'],
                ['name' => 'User'],
            ],
            'permissions' => [
                'view_product',
                'create_product',
                'edit_product',
                'delete_product',
            ]
        ]);
    }

    // ✅ Get users by role
    public function usersByRole($role)
    {
        $users = User::where('role', $role)->get();

        return response()->json($users);
    }

    // ✅ Assign permissions
    public function assignUserPermissions(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'permissions' => 'required|array'
        ]);

        // remove old
        UserPermission::where('user_id', $request->user_id)->delete();

        foreach ($request->permissions as $perm) {
            UserPermission::create([
                'user_id' => $request->user_id,
                'permission' => $perm
            ]);
        }

        return response()->json([
            'success' => true
        ]);
    }

    // ✅ (OPTIONAL) for roles-list page
    public function allUsersWithPermissions()
    {
        $users = User::with('permissions')->get();

         $data = $users->map(function ($user) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'role' => $user->role,
            'permissions' => $user->permissions->map(function ($p) {
                return $p->permission;
            }),
        ];
    });

    return response()->json($data);
    }
}