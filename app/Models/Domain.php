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

    public function getAllForUser($user_id) {
        $stmt = $this->db->prepare("SELECT * FROM domains WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    public function findByIdAndUser($id, $user_id) {
        $stmt = $this->db->prepare("SELECT * FROM domains WHERE id = ? AND user_id = ? LIMIT 1");
        $stmt->execute([$id, $user_id]);
        return $stmt->fetch();
    }

    public function create($user_id, $domain_name) {
        $stmt = $this->db->prepare("INSERT INTO domains (user_id, domain) VALUES (?, ?)");
        return $stmt->execute([$user_id, $domain_name]);
    }

    public function delete($id, $user_id) {
        $stmt = $this->db->prepare("DELETE FROM domains WHERE id = ? AND user_id = ?");
        return $stmt->execute([$id, $user_id]);
    }
}

