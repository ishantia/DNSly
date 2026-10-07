<?php

namespace App\Controllers;

use App\Services\Database;
use PDO;

class AdminController {
    
    private function checkAdmin() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            redirect('/dashboard');
        }
    }

    public function index() {
        $this->checkAdmin();

        $db = Database::getInstance()->getConnection();

        // Get stats
        $stats = [
            'total_users' => $db->query("SELECT COUNT(*) FROM users")->fetchColumn(),
            'total_domains' => $db->query("SELECT COUNT(*) FROM domains")->fetchColumn(),
            'total_records' => $db->query("SELECT COUNT(*) FROM dns_records")->fetchColumn(),
            'recent_activity' => $db->query("SELECT COUNT(*) FROM activity_logs WHERE created_at >= NOW() - INTERVAL 24 HOUR")->fetchColumn(),
        ];

        // Recent users
        $recent_users = $db->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 5")->fetchAll();
        
        // Recent activity
        $recent_activity = $db->query("
            SELECT a.*, u.username 
            FROM activity_logs a 
            LEFT JOIN users u ON a.user_id = u.id 
            ORDER BY a.created_at DESC LIMIT 10
        ")->fetchAll();

        view('admin.index', [
            'title' => 'Admin Dashboard - ' . env('APP_NAME'),
            'stats' => $stats,
            'recent_users' => $recent_users,
            'recent_activity' => $recent_activity
        ]);
    }
}
