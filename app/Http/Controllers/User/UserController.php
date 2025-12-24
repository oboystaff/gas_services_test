<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;


class UserController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->can('users.view')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "User Page";

        $users = User::orderBy('created_at', 'DESC')
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            })
            ->get();

        return view('users.index', compact('users', 'pageTitle'));
    }

    public function create(Request $request)
    {
        if (!auth()->user()->can('users.create')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "User Page";

        $branches = Branch::orderBy('created_at', 'DESC')
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('id', $request->user()->branch_id);
            })
            ->get();

        $roles = Role::orderBy('name', 'ASC')->get();

        return view('users.create', compact('branches', 'roles', 'pageTitle'));
    }

    public function store(CreateUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $data['created_by'] = $request->user()->id ?? null;
        $data['user_role'] = $request->input('role');

        $branchManager = User::where('branch_id', $request->input('branch_id'))
            ->whereHas('role', function ($query) {
                $query->where('name', 'LIKE', '%Branch Manager%');
            })
            ->first();

        if (!empty($branchManager) && $branchManager->user_role === $request->input('role')) {
            return back()->withErrors('Branch Manager already exist, and you can only have one Branch Manager for the selected branch');
        }

        $user = User::create($data);

        $user->roles()->sync($request->validated('role'));

        return redirect()->route('users.index')->with('status', 'User created successfully.');
    }

    public function show(User $user)
    {
        $pageTitle = "User Page";

        return view('users.show', compact('user', 'pageTitle'));
    }

    public function edit(Request $request, User $user)
    {
        if (!auth()->user()->can('users.update')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "User Page";

        $branches = Branch::orderBy('created_at', 'DESC')
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('id', $request->user()->branch_id);
            })
            ->get();

        $roles = Role::orderBy('name', 'ASC')->get();
        $userRole = $user->roles()->pluck('id')->toArray();

        return view('users.edit', compact('user', 'branches', 'roles', 'userRole', 'pageTitle'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        $data['user_role'] = $request->input('role');

        if (empty($request->validated('password'))) {
            $data['password'] = $user->password;
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $branchManager = User::where('branch_id', $request->input('branch_id'))
            ->whereHas('role', function ($query) {
                $query->where('name', 'LIKE', '%Branch Manager%');
            })
            ->first();

        if ($user->user_role !== $data['user_role']) {
            if (!empty($branchManager) && $branchManager->user_role === $request->input('role')) {
                return back()->withErrors('Branch Manager already exist, and you can only have one Branch Manager for the selected branch');
            }
        }

        $user->update($data);

        $user->roles()->sync($request->validated('role'));

        return redirect()->route('users.index')->with('status', 'User updated successfully.');
    }
}
