<?php

namespace App\Controllers;

use App\Models\ActivityLog;

class ActivityController {
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
        }

        $activityModel = new ActivityLog();
        $logs = $activityModel->getAllForUser($_SESSION['user_id']);

        view('activity.index', [
            'title' => 'Activity - ' . env('APP_NAME'),
            'logs' => $logs
        ]);
    }
}
