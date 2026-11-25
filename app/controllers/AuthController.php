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
        csrf_verify();
        $identifier = trim((string) ($_POST['email'] ?? ''));
        $password   = (string) ($_POST['password'] ?? '');

        $stmt = db()->prepare(
            'SELECT * FROM users WHERE email = :email OR student_number = :number LIMIT 1'
        );
        $stmt->execute([':email' => $identifier, ':number' => $identifier]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['auth_user'] = [
                'id'                => (int) $user['id'],
                'name'              => $user['name'],
                'email'             => $user['email'],
                'student_number'    => $user['student_number'],
                'role'              => $user['role'],
                'course'            => $user['course'],
                'organization'      => $user['organization'],
                'organization_role' => $user['organization_role'],
            ];

            $this->redirect($user['role'] === 'admin' ? '/admin' : '/dashboard');
        }

        set_flash('Invalid email / student number or password.', 'error');
        $this->redirect('/login');
    }

    public function showRegister(): void
    {
        $this->view('auth/register', ['title' => 'Register'], 'layouts/auth');
    }

    public function register(): void
    {
        csrf_verify();
        $name     = trim((string) ($_POST['name'] ?? ''));
        $email    = trim((string) ($_POST['email'] ?? ''));
        $number   = trim((string) ($_POST['student_number'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $confirm  = (string) ($_POST['password_confirmation'] ?? '');
        $role     = (string) ($_POST['role'] ?? 'student');
        $role     = in_array($role, ['student', 'admin'], true) ? $role : 'student';

        if ($name === '' || $email === '' || $number === '' || $password === '') {
            set_flash('All fields are required.', 'error');
            $this->redirect('/register');
        }

        if ($password !== $confirm) {
            set_flash('Passwords do not match.', 'error');
            $this->redirect('/register');
        }

        $check = db()->prepare(
            'SELECT id FROM users WHERE email = :email OR student_number = :number LIMIT 1'
        );
        $check->execute([':email' => $email, ':number' => $number]);

        if ($check->fetch()) {
            set_flash('An account with that email or student number already exists.', 'error');
            $this->redirect('/register');
        }

        $stmt = db()->prepare(
            'INSERT INTO users (name, email, student_number, role, password_hash)
             VALUES (:name, :email, :number, :role, :hash)'
        );
        $stmt->execute([
            ':name'   => $name,
            ':email'  => $email,
            ':number' => $number,
            ':role'   => $role,
            ':hash'   => password_hash($password, PASSWORD_DEFAULT),
        ]);

        set_flash('Account created. You can now log in.', 'success');
        $this->redirect('/login');
    }

    public function logout(): void
    {
        csrf_verify();
        session_destroy();
        $this->redirect('/login');
    }
}
