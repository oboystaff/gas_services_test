<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\User\CreateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $data = User::orderBy('created_at', 'DESC')
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            })
            ->with(['branch', 'role'])
            ->get();

        return response()->json([
            'message' => 'Get all users',
            'data' => $data
        ]);
    }

    public function store(CreateUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $data['created_by'] = $request->user()->id ?? null;

        $user = User::create($data);

        return response()->json([
            'message' => 'User created successfully',
            'data' => $user
        ]);
    }

    public function show($id)
    {
        $user = User::query()
            ->with(['branch', 'role'])
            ->where('id', $id)
            ->first();

        if (empty($user)) {
            return response()->json([
                'message' => 'User not found'
            ], 422);
        }

        return response()->json([
            'message' => 'Get particular user',
            'data' => $user
        ]);
    }

    public function update() {}
}
