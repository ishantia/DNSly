<?php

namespace App\Models;

use App\Services\Database;
use PDO;

class Record {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function countForUser($user_id) {
        $stmt = $this->db->prepare("SELECT COUNT(r.id) FROM dns_records r JOIN domains d ON r.domain_id = d.id WHERE d.user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchColumn();
    }

    public function getAllForDomain($domain_id) {
        $stmt = $this->db->prepare("SELECT * FROM dns_records WHERE domain_id = ? ORDER BY type ASC, name ASC");
        $stmt->execute([$domain_id]);
        return $stmt->fetchAll();
    }

    public function findById($id, $domain_id) {
        $stmt = $this->db->prepare("SELECT * FROM dns_records WHERE id = ? AND domain_id = ? LIMIT 1");
        $stmt->execute([$id, $domain_id]);
        return $stmt->fetch();
    }

    public function create($domain_id, $type, $name, $value, $ttl) {
        $stmt = $this->db->prepare("INSERT INTO dns_records (domain_id, type, name, value, ttl) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$domain_id, $type, $name, $value, $ttl]);
    }

    public function update($id, $domain_id, $type, $name, $value, $ttl) {
        $stmt = $this->db->prepare("UPDATE dns_records SET type = ?, name = ?, value = ?, ttl = ? WHERE id = ? AND domain_id = ?");
        return $stmt->execute([$type, $name, $value, $ttl, $id, $domain_id]);
    }

    public function delete($id, $domain_id) {
        $stmt = $this->db->prepare("DELETE FROM dns_records WHERE id = ? AND domain_id = ?");
        return $stmt->execute([$id, $domain_id]);
    }
}

