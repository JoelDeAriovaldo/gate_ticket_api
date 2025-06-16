<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use App\Services\UserService;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * UserController
 *
 * Handles admin-only user management endpoints (CRUD).
 */
class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->middleware('role:admin');
        $this->userService = $userService;
    }

    /**
     * List users (paginated).
     */
    public function index(Request $request)
    {
        $users = User::paginate($request->get('per_page', 15));
        return new UserCollection($users);
    }

    /**
     * Store a new user.
     */
    public function store(CreateUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);
        $user = $this->userService->createUser($data);

        return $this->successResponse(
            new UserResource($user),
            'User created successfully',
            201
        );
    }

    /**
     * Show a user.
     */
    public function show(User $user)
    {
        return $this->successResponse(
            new UserResource($user),
            'User retrieved'
        );
    }

    /**
     * Update a user.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        $user = $this->userService->updateUser($user, $data);

        return $this->successResponse(
            new UserResource($user),
            'User updated successfully'
        );
    }

    /**
     * Delete a user.
     */
    public function destroy(User $user)
    {
        $this->userService->deleteUser($user);

        return $this->successResponse(null, 'User deleted successfully');
    }
}
