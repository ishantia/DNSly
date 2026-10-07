<?php

namespace App\Models;

use App\Services\Database;
use PDO;

class ActivityLog {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getRecentForUser($user_id, $limit = 5) {
        $stmt = $this->db->prepare("SELECT * FROM activity_logs WHERE user_id = ? ORDER BY created_at DESC LIMIT " . (int)$limit);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    public function getAllForUser($user_id) {
        $stmt = $this->db->prepare("SELECT * FROM activity_logs WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }
}

