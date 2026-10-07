<?php

namespace App\Controllers;

use App\Models\User;

class AuthController {
    
    public function showLogin() {
        if (isset($_SESSION['user_id'])) {
            redirect('/dashboard');
        }
        view('auth.login', ['title' => 'Login - ' . env('APP_NAME')]);
    }

    public function login() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $error = '';

        if (empty($email) || empty($password)) {
            $error = 'Email and password are required.';
        } else {
            $userModel = new User();
            $user = $userModel->findByEmail($email);
            
            if ($user && password_verify($password, $user['password_hash'])) {
                if ($user['status'] !== 'active') {
                    $error = 'Account is suspended.';
                } else {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    
                    // Log activity
                    $db = \App\Services\Database::getInstance()->getConnection();
                    $stmt = $db->prepare("INSERT INTO activity_logs (user_id, action, description, ip_address) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$user['id'], 'login', 'User logged in', $_SERVER['REMOTE_ADDR'] ?? null]);

                    redirect('/dashboard');
                }
            } else {
                $error = 'Invalid credentials.';
            }
        }

        view('auth.login', ['error' => $error, 'email' => $email, 'title' => 'Login - ' . env('APP_NAME')]);
    }

    public function showRegister() {
        if (isset($_SESSION['user_id'])) {
            redirect('/dashboard');
        }
        view('auth.register', ['title' => 'Register - ' . env('APP_NAME')]);
    }

    public function register() {
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';
        $error = '';

        if (empty($username) || empty($email) || empty($password)) {
            $error = 'All fields are required.';
        } elseif ($password !== $password_confirm) {
            $error = 'Passwords do not match.';
        } elseif (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters.';
        } else {
            $userModel = new User();
            if ($userModel->findByEmail($email)) {
                $error = 'Email is already registered.';
            } elseif ($userModel->findByUsername($username)) {
                $error = 'Username is already taken.';
            } else {
                if ($userModel->create($username, $email, $password)) {
                    // Log activity for registration - need the new user id
                    $user = $userModel->findByEmail($email);
                    $db = \App\Services\Database::getInstance()->getConnection();
                    $stmt = $db->prepare("INSERT INTO activity_logs (user_id, action, description, ip_address) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$user['id'], 'register', 'User registered', $_SERVER['REMOTE_ADDR'] ?? null]);

                    redirect('/login?registered=1');
                } else {
                    $error = 'Registration failed. Please try again.';
                }
            }
        }

        view('auth.register', [
            'error' => $error, 
            'username' => $username, 
            'email' => $email,
            'title' => 'Register - ' . env('APP_NAME')
        ]);
    }

    public function logout() {
        if (isset($_SESSION['user_id'])) {
            $db = \App\Services\Database::getInstance()->getConnection();
            $stmt = $db->prepare("INSERT INTO activity_logs (user_id, action, description, ip_address) VALUES (?, ?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], 'logout', 'User logged out', $_SERVER['REMOTE_ADDR'] ?? null]);
        }
        session_destroy();
        redirect('/');
    }
}
