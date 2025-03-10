<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->validate([
            'role_name' => 'required',
        ]);

        Role::create([
            'role_name' => $data['role_name'],
        ]);

        return response()->json([
            'message' => 'Role Created Successfully',
        ]);
    }
}
