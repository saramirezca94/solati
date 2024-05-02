<?php

namespace App\Http\Controllers\Api\V1;

use App\Dao\UserDao;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\{Request, Response};
use App\Http\Requests\{CreateUserRequest, UpdateUserRequest};
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(): AnonymousResourceCollection
    {
        $users = $this->userService->getAll();

        return UserResource::collection($users);
    }

    public function store(CreateUserRequest $request): JsonResponse
    {
        $result = $this->userService->save($request->validated());

        return response()->json([
            'message' => $result['message'],              
        ], $result['code']);
    }

    public function show($id): JsonResponse
    {
        $user = $this->userService->getById($id);

        if(!$user)
        {
            return response()->json([
                'message' => 'User not found',              
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json([
            new UserResource($user)       
        ], Response::HTTP_OK);
    }

    public function update(UpdateUserRequest $request, $id): JsonResponse
    {        
        $result = $this->userService->update(data: $request->validated(), id: $id);

        return response()->json([
            'message' => $result['message'],            
        ], $result['code']);
    }

    public function destroy(int $id): JsonResponse
    {
        $result = $this->userService->delete($id);

        return response()->json([
            'message' => $result['message'],            
        ], $result['code']);
    }
}
