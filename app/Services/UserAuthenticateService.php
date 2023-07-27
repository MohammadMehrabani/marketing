<?php

namespace App\Services;
use App\Contracts\UserRepositoryInterface;
use App\Contracts\UserAuthenticateServiceInterface;
use App\DTO\UserDto;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserAuthenticateService implements UserAuthenticateServiceInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function authenticate(array $arguments)
    {
        $arguments = UserDto::fromArray($arguments);

        $user = $this->userRepository->findByMobile($arguments);

        if ($user) {
            if ($user->password && $user->mobile_verified_at) {
                return [
                    'mobile' => $user->mobile,
                    'newUser' => false,
                    'nextPage' => 'login'
                ];
            }

            return [
                'mobile' => $user->mobile,
                'newUser' => true,
                'nextPage' => 'verifyOtp'
            ];
        }

        $newUser = $this->userRepository->create($arguments);
        if ($newUser) {
            return [
                'mobile' => $newUser->mobile,
                'newUser' => true,
                'nextPage' => 'verifyOtp'
            ];
        }
    }

    public function sendOtp(array $arguments)
    {
        $arguments = UserDto::fromArray($arguments);

        $user = $this->userRepository->findByMobile($arguments);

        $code = rand(10000, 99999);

        if ($user && !Cache::has('otp_'.$arguments->mobile)) {

            Cache::add('otp_'.$arguments->mobile, $code, now()->addMinutes(2));

            // If there was an SMS panel
            // e.g. send otp with sms using queue (job)
            //if (env('APP_ENV') == 'production')
            //SendLoginCodeJob::dispatch($arguments->mobile, $code)->onQueue('otp');

            return true;
        }

        return false;
    }

    public function verifyOtp(array $arguments)
    {
        $arguments = UserDto::fromArray($arguments);

        $user = $this->userRepository->findByMobile($arguments);

        if ($user &&
            Cache::has('otp_'.$arguments->mobile) &&
            Cache::get('otp_'.$arguments->mobile) == $arguments->otp
        ) {
            if (!$user->mobile_verified_at) {

                $this->userRepository->update(
                    $user,
                    UserDto::fromArray(['mobileVerifiedAt' => now()->format('Y-m-d H:i:s')])
                );

                return [
                    'mobile' => $arguments->mobile,
                    'newUser' => true,
                    'next' => 'register'
                ];
            }

            $existsResetToken = DB::table('password_reset_tokens')->where('mobile', $arguments->mobile)->first();
            if(empty($existsResetToken)) {
                DB::table('password_reset_tokens')->insert([
                    'mobile' => $arguments->mobile,
                    'token' => Str::random(32),
                    'created_at' => now()->format('Y-m-d H:i:s')
                ]);
            }

            return [
                'mobile' => $arguments->mobile,
                'newUser' => false,
                'next' => 'changePassword'
            ];
        }

        throw ValidationException::withMessages(['code' => 'otp is invalid.']);
    }

    public function register(array $arguments)
    {
        $arguments = UserDto::fromArray($arguments);

        $user = $this->userRepository->findByMobile($arguments);

        if ($user && $user->mobile_verified_at && !$user->password) {

            $this->userRepository->update($user, $arguments);

            $token = auth()->attempt([
                'mobile' => $arguments->mobile, 'password' => $arguments->password, 'type' => $arguments->type
            ]);

            if (! $token) {
                throw new AuthenticationException();
            }

            return $this->respondWithToken($token);
        }

        throw new ApiException('incorrect request', 400);
    }

    public function login(array $arguments)
    {
        $arguments = UserDto::fromArray($arguments);

        if (! $token = auth()->attempt(['mobile' => $arguments->mobile, 'password' => $arguments->password])) {
            throw new AuthenticationException();
        }

        return $this->respondWithToken($token);
    }

    public function refresh()
    {
        try {
            return $this->respondWithToken(auth()->refresh());
        } catch (JWTException $e) {
            throw new AuthenticationException($e->getMessage());
        }
    }

    public function changePassword(array $arguments)
    {
        $arguments = UserDto::fromArray($arguments);

        $user = $this->userRepository->findByMobile($arguments);

        if ($user) {

            $token = DB::table('password_reset_tokens')
                ->where('mobile', $user->mobile)
                ->where('token', $arguments->resetPasswordToken)
                ->first();

            if ($token) {

                $this->userRepository->update(
                    $user,
                    UserDto::fromArray(['password' => $arguments->password])
                );

                DB::table('password_reset_tokens')
                    ->where('mobile', $user->mobile)
                    ->where('token', $arguments->resetPasswordToken)
                    ->delete();

                return $this->refresh();
            }
        }

        throw new ApiException('incorrect request', 400);
    }

    public function getTokenPasswordReset(array $arguments)
    {
        $arguments = UserDto::fromArray($arguments);

        $user = $this->userRepository->findByMobile($arguments);

        if ($user) {
            $token = DB::table('password_reset_tokens')
                ->where('mobile', $user->mobile)
                ->orderByDesc('created_at')
                ->first();

            return ['token' => $token->token ?? null];
        }

        throw new ApiException('user not found', 404);
    }

    private function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }
}
