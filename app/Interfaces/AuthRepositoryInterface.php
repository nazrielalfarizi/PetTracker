<?php

namespace App\Interfaces;

use App\Interfaces\AuthRepositoryInterface;

interface AuthRepositoryInterface
{
    public function login(array $credentials);
    public function register(array $data);
    public function logout();
}
