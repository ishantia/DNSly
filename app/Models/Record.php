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
}
