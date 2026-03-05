<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Contracts\Services\AuthServiceInterface;
use App\LdapClasses\adLDAP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class AuthService implements AuthServiceInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    public function attempt(Request $request): ?int
    {
        if ($request->input('login_as') === 'ldap-login') {
            return $this->attemptLdap($request);
        }

        return $this->attemptCredentials($request);
    }

    private function attemptLdap(Request $request): ?int
    {
        $email = (string) $request->input('user_id');
        $username = explode('@', $email)[0] ?? '';
        if ($username === '') {
            return null;
        }

        $internalEmail = $username . '@fumaco.local';
        $externalEmail = $username . '@fumaco.com';
        $user = $this->userRepository->findOneByInternalOrExternalEmail($internalEmail, $externalEmail);

        if ($user === null) {
            return null;
        }

        $userId = $user->id ?? null;
        $userUserId = $user->user_id ?? null;
        $userEmail = $user->email ?? null;

        if ($userId === null || $userUserId === null) {
            return null;
        }

        if ((string) $userEmail === '') {
            return null;
        }

        $adldap = new adLDAP();
        $usernameForLdap = explode('@', (string) $userEmail)[0] ?? '';
        $authenticated = $adldap->user()->authenticate($usernameForLdap, (string) $request->input('password'));

        if ($authenticated !== true) {
            return null;
        }

        if (! Auth::loginUsingId((int) $userId)) {
            return null;
        }

        $this->userRepository->updateLastLogin((string) $userUserId);

        return (int) $userId;
    }

    private function attemptCredentials(Request $request): ?int
    {
        $remember = (bool) $request->input('remember', false);
        $attempt = Auth::attempt([
            'user_id' => $request->input('user_id'),
            'password' => $request->input('password'),
        ], $remember);

        if (! $attempt) {
            return null;
        }

        $userId = $request->input('user_id');
        if ($userId === null || $userId === '') {
            return null;
        }

        $user = $this->userRepository->findByUserId((string) $userId);
        $id = $user !== null && isset($user->id) ? (int) $user->id : null;
        $this->userRepository->updateLastLogin((string) $userId);

        return $id;
    }
}
