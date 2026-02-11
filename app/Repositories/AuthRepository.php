<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;
use App\Interfaces\AuthRepositoryInterface;

class AuthRepository implements AuthRepositoryInterface
{
    public function login(array $credentials)
    {
        return Auth::attempt($credentials);
    }

    public function register(array $data)
    {
        // Implementation of registration logic
    }

    public function logout()
    {
        return Auth::logout();
    }
}

