<?php

namespace App\Http\Controllers\v1\UserManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AddCopilotRequest;
use App\Http\Requests\Auth\CreateUserRequest;
use App\Http\Requests\Auth\SuspendUserRequest;
use App\Http\Requests\Auth\UpdateUserRequest;
use App\Services\UserManagement\IUserManagementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use function request;

class UserManagementController extends Controller
{
    private IUserManagementService $userMgmtService;

    function __construct(IUserManagementService $userMgmtService)
    {
        $this->userMgmtService = $userMgmtService;
    }

    public function createUser(CreateUserRequest $request): JsonResponse
    {
        $payload = $request->all();
        return $this->successResponse($this->userMgmtService->createUser($payload));
    }

    public function getCopilots(int $merchantId): JsonResponse
    {
        return $this->successResponse($this->userMgmtService->getCopilots(request()->user(), $merchantId));
    }

    public function getUserByEmailOrPhone(string $searchValue): JsonResponse
    {
        return $this->successResponse($this->userMgmtService->getUserByEmailOrPhone($searchValue));
    }

    public function updateUser(UpdateUserRequest $request, int $id): Response
    {
        $this->userMgmtService->updateUser($request->user(), $id, $request->validated());
        return $this->noContent();
    }

    public function suspendUser(SuspendUserRequest $request): Response
    {
        $this->userMgmtService->suspendUser($request->user(), $request->get('user_id'), $request->validated());
        return $this->noContent();
    }

    public function addCopilot(AddCopilotRequest $request, int $merchantId): JsonResponse
    {
        return $this->successResponse($this->userMgmtService->addCopilot($request->user(), $merchantId, $request->validated()));
    }

    public function removeCopilot(int $merchantId, int $userId): Response
    {
        $this->userMgmtService->removeCopilot(request()->user(), $merchantId, $userId);
        return $this->noContent();
    }

    public function blockCopilot(int $merchantId, int $userId): Response
    {
        $this->userMgmtService->blockCopilot(request()->user(), $merchantId, $userId);
        return $this->noContent();
    }

    public function unBlockCopilot(int $merchantId, int $userId): Response
    {
        $this->userMgmtService->unBlockCopilot(request()->user(), $merchantId, $userId);
        return $this->noContent();
    }

    public function suspendCopilot(SuspendUserRequest $request, int $merchantId): Response
    {
        $this->userMgmtService->suspendCopilot($request->user(), $merchantId, $request->get('user_id'), $request->validated());
        return $this->noContent();
    }

    public function unSuspendCopilot(SuspendUserRequest $request, int $merchantId): Response
    {
        $this->userMgmtService->unSuspendCopilot($request->user(), $merchantId, $request->get('user_id'));
        return $this->noContent();
    }
}
