<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Services\UserService;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {}

    public function index()
    {
        return response()->json([
            'data' => $this->userService->list()
        ]);
    }

    public function show(User $user)
    {
        return response()->json([
            'data' => $user
        ]);
    }

    /**
     * Admin creates a user
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|email|unique:users,email",
            "password" => "required|min:6",
        ]);

        $data['password'] = Hash::make($data['password']);

        $user = $this->userService->create($data);

        return response()->json([
            'data' => $user
        ], 201);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            "name" => "sometimes|required|string|max:255",
            "email" => "sometimes|required|email|unique:users,email," . $user->id,
            "password" => "sometimes|nullable|min:6",
            "phone" => "sometimes|nullable|string|max:20",
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $updatedUser = $this->userService->update($user, $data);

        return response()->json([
            'data' => $updatedUser
        ]);
    }

    public function destroy(User $user)
    {
        $this->userService->delete($user);

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }

    public function attach(Request $request, User $user)
    {
        $data = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id'
        ]);

        $this->userService->attachProducts(
            $user,
            $data['product_ids']
        );

        return response()->json([
            'message' => 'Products attached to user successfully'
        ]);
    }
}
