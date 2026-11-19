<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

final class AuthController extends Controller
{
    public function showLogin(): void
    {
        $this->view('auth/login', ['title' => 'Log in'], 'layouts/auth');
    }

    public function login(): void
    {
        // Authentication will be implemented once the database is added.
        $this->redirect('/dashboard');
    }

    public function showRegister(): void
    {
        $this->view('auth/register', ['title' => 'Register'], 'layouts/auth');
    }

    public function register(): void
    {
        // Registration will be implemented once the database is added.
        $this->redirect('/login');
    }

    public function logout(): void
    {
        $this->redirect('/login');
    }
}
