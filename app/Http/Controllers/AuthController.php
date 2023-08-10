<?php

namespace App\Http\Controllers;

use App\Contracts\UserAuthenticateServiceInterface;
use App\DTO\UserDto;
use App\Http\Requests\User\AuthenticateRequest;
use App\Http\Requests\User\GetTokenPasswordResetRequest;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\ResetPasswordRequest;
use App\Http\Requests\User\SendOtpRequest;
use App\Http\Requests\User\VaerifyOtpRequest;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    public function __construct(
        private UserAuthenticateServiceInterface $userAuthenticateService
    ) {}

    public function authenticate(AuthenticateRequest $request)
    {
        $dto = UserDto::fromRequest($request->safe());

        $data = $this->userAuthenticateService->authenticate($dto);

        return response()->success($data);
    }

    public function verifyOtp(VaerifyOtpRequest $request)
    {
        $dto = UserDto::fromRequest($request->safe());

        $data = $this->userAuthenticateService->verifyOtp($dto);

        return response()->success($data);
    }

    public function sendOtp(SendOtpRequest $request)
    {
        $dto = UserDto::fromRequest($request->safe());

        $data = $this->userAuthenticateService->sendOtp($dto);

        $code = env('APP_ENV') == 'local' && Cache::has('otp_'.$dto->mobile)
                ? ' otp: '.Cache::get('otp_'.$dto->mobile)
                : '';

        if ($data && $code)
            return response()->success('otp sent successfully.'.$code);
    }

    public function register(RegisterRequest $request)
    {
        $dto = UserDto::fromRequest($request->safe());

        $data = $this->userAuthenticateService->register($dto);

        return response()->success($data);
    }

    public function login(LoginRequest $request)
    {
        $dto = UserDto::fromRequest($request->safe());

        $data = $this->userAuthenticateService->login($dto);

        return response()->success($data);
    }

    public function me()
    {
        $user = auth()->user();

        return response()->success($user);
    }

    public function logout()
    {
        auth()->logout();

        return response()->success('Successfully logged out');
    }

    public function refresh()
    {
        $data = $this->userAuthenticateService->refresh();

        return response()->success($data);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $dto = UserDto::fromRequest($request->safe());

        $data = $this->userAuthenticateService->resetPassword($dto);

        if ($data)
            return response()->success($data);
    }

    public function getTokenPasswordReset(GetTokenPasswordResetRequest $request)
    {
        $dto = UserDto::fromRequest($request->safe());

        $data = $this->userAuthenticateService->getTokenPasswordReset($dto);

        return response()->success($data);
    }
}
