<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Contracts\Services\AuthServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\PostLoginCommandRunner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    protected string $redirectTo = '/home';

    public function __construct(
        private readonly AuthServiceInterface $authService,
        private readonly PostLoginCommandRunner $postLoginCommandRunner
    ) {
        $this->middleware('guest', ['except' => ['logout', 'userLogout']]);
    }

    public function userLogout(): \Illuminate\Http\RedirectResponse
    {
        Auth::guard('web')->logout();

        return redirect('/');
    }

    public function showLoginForm(): \Illuminate\View\View
    {
        return view('home');
    }

    /**
     * Authenticate user (LDAP or credentials). Returns 1 on success, null on failure.
     * Same route and response contract as before.
     */
    public function userLogin(LoginRequest $request): int|null
    {
        DB::beginTransaction();
        try {
            $id = $this->authService->attempt($request);
            if ($id !== null) {
                $this->postLoginCommandRunner->runForUser($id);
            }

            DB::commit();

            return $id !== null ? 1 : null;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
