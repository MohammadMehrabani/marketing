<?php

namespace App\Http\Controllers;

use App\Contracts\UserAuthenticateServiceInterface;
use App\Rules\Mobile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    public function __construct(private UserAuthenticateServiceInterface $userAuthenticateService)
    {
    }

    public function authenticate(Request $request)
    {
        $request->validate($inputs = [
            'mobile' => ['required', 'digits:11', new Mobile()]
        ]);

        $data = $this->userAuthenticateService->authenticate(
            $this->getParams(array_keys($inputs))
        );

        return response()->success($data);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate($inputs = [
            'mobile' => ['required', 'digits:11', new Mobile()],
            'code' => ['required', 'digits:5'],
        ]);

        $data = $this->userAuthenticateService->verifyOtp(
            $this->getParams(array_keys($inputs))
        );

        return response()->success($data);
    }

    public function sendOtp(Request $request)
    {
        $request->validate($inputs = [
            'mobile' => ['required', 'digits:11', new Mobile()]
        ]);

        $data = $this->userAuthenticateService->sendOtp(
            $this->getParams(array_keys($inputs))
        );

        $code = env('APP_ENV') == 'local' && Cache::has('otp_'.$request->mobile)
            ? ' otp: '.Cache::get('otp_'.$request->mobile)
            : '';

        if ($data)
            return response()->success('otp sent successfully.'.$code);
        else
            return response()->error('your previous otp has not yet expired.');
    }

    public function register(Request $request)
    {
        $request->validate($inputs = [
            'mobile' => ['required', 'digits:11', new Mobile()],
            'password' => ['required', 'string', 'confirmed', 'min:6'],
            'firstname' => ['required', 'string'],
            'lastname' => ['required', 'string'],
            'type' => ['required', 'in:merchant,marketer'],
            'name' => ['required', 'string'],
            'url' => ['required', 'url']
        ]);

        $data = $this->userAuthenticateService->register(
            $this->getParams(array_keys($inputs))
        );

        return response()->success($data);
    }

    public function login(Request $request)
    {
        $request->validate($inputs = [
            'mobile' => ['required', 'digits:11', new Mobile()],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $data = $this->userAuthenticateService->login(
            $this->getParams(array_keys($inputs))
        );

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

    public function changePassword(Request $request)
    {
        $request->validate($inputs = [
            'mobile' => ['required', 'digits:11', new Mobile()],
            'password' => ['required', 'string', 'confirmed', 'min:6'],
            'token' => ['required']
        ]);

        $data = $this->userAuthenticateService->changePassword(
            $this->getParams(array_keys($inputs))
        );

        if ($data)
            return response()->success($data);
    }

    public function getTokenPasswordReset(Request $request)
    {
        $request->validate($inputs = [
            'mobile' => ['required', 'digits:11', new Mobile()]
        ]);

        $data = $this->userAuthenticateService->getTokenPasswordReset(
            $this->getParams(array_keys($inputs))
        );

        return response()->success($data);
    }

    private function getParams($inputs = []): array
    {
        return request()->only($inputs);
    }
}
