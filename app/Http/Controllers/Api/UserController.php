<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use ApiResponse;

    public function index()
    {
        return $this->success(User::all(), 'Users retrieved successfully');
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return $this->success($user, 'User created successfully', 201);
    }

    public function show(User $user)
    {
        return $this->success($user, 'User retrieved successfully');
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->only(['name', 'email', 'role']));
        return $this->success($user, 'User updated successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return $this->success(null, 'User deleted successfully');
    }
}
