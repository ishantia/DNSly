<?php

namespace App\Models;

use App\Services\Database;
use PDO;

class Domain {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function countForUser($user_id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM domains WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchColumn();
    }

    public function getActiveCountForUser($user_id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM domains WHERE user_id = ? AND status = 'active'");
        $stmt->execute([$user_id]);
        return $stmt->fetchColumn();
    }

    public function getRecentForUser($user_id, $limit = 5) {
        $stmt = $this->db->prepare("SELECT * FROM domains WHERE user_id = ? ORDER BY created_at DESC LIMIT " . (int)$limit);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }
}
