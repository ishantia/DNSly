<?php

namespace App\Controllers;

use App\Models\Domain;
use App\Models\Record;
use App\Models\ActivityLog;

class DashboardController {
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
        }

        $user_id = $_SESSION['user_id'];

        $domainModel = new Domain();
        $recordModel = new Record();
        $activityModel = new ActivityLog();

        $stats = [
            'total_domains' => $domainModel->countForUser($user_id),
            'active_domains' => $domainModel->getActiveCountForUser($user_id),
            'total_records' => $recordModel->countForUser($user_id),
        ];

        $recent_domains = $domainModel->getRecentForUser($user_id, 5);
        $recent_activity = $activityModel->getRecentForUser($user_id, 5);

        view('dashboard.index', [
            'title' => 'Dashboard - ' . env('APP_NAME'),
            'stats' => $stats,
            'recent_domains' => $recent_domains,
            'recent_activity' => $recent_activity
        ]);
    }
}
