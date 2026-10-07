<?php

namespace App\Controllers;

use App\Models\User;

class SettingsController {
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
        }

        $userModel = new User();
        $user = $userModel->findById($_SESSION['user_id']);

        view('settings.index', [
            'title' => 'Settings - ' . env('APP_NAME'),
            'user' => $user
        ]);
    }

    public function updatePassword() {
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
        }

        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        $userModel = new User();
        $user = $userModel->findById($_SESSION['user_id']);

        $error = '';
        $success = '';

        if (empty($current) || empty($new) || empty($confirm)) {
            $error = 'All fields are required.';
        } elseif (!password_verify($current, $user['password_hash'])) {
            $error = 'Current password is incorrect.';
        } elseif ($new !== $confirm) {
            $error = 'New passwords do not match.';
        } elseif (strlen($new) < 6) {
            $error = 'New password must be at least 6 characters.';
        } else {
            if ($userModel->updatePassword($user['id'], $new)) {
                $success = 'Password updated successfully.';
                // log activity
                $db = \App\Services\Database::getInstance()->getConnection();
                $stmt = $db->prepare("INSERT INTO activity_logs (user_id, action, description, ip_address) VALUES (?, ?, ?, ?)");
                $stmt->execute([$user['id'], 'change_password', 'Changed password', $_SERVER['REMOTE_ADDR'] ?? null]);
            } else {
                $error = 'Failed to update password.';
            }
        }

        view('settings.index', [
            'title' => 'Settings - ' . env('APP_NAME'),
            'user' => $user,
            'password_error' => $error,
            'password_success' => $success
        ]);
    }

    public function updateTheme() {
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
        }

        $theme = $_POST['theme'] ?? 'system';
        if (in_array($theme, ['light', 'dark', 'system'])) {
            $userModel = new User();
            $userModel->updateTheme($_SESSION['user_id'], $theme);
            $_SESSION['theme'] = $theme;
        }

        redirect('/settings');
    }
}
