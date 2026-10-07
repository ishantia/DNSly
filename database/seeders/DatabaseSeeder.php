<?php

require_once __DIR__ . '/../../public/index.php'; // to load env and autoloader

$db = \App\Services\Database::getInstance()->getConnection();

echo "Seeding database...\n";

// Clear existing
$db->exec("SET FOREIGN_KEY_CHECKS = 0;");
$db->exec("TRUNCATE TABLE users;");
$db->exec("TRUNCATE TABLE domains;");
$db->exec("TRUNCATE TABLE dns_records;");
$db->exec("TRUNCATE TABLE activity_logs;");
$db->exec("SET FOREIGN_KEY_CHECKS = 1;");

$password_hash = password_hash('password123', PASSWORD_DEFAULT);

// Seed Admin
$db->exec("INSERT INTO users (username, email, password_hash, role) VALUES ('admin', 'admin@dnsly.local', '$password_hash', 'admin')");

// Seed Demo User
$db->exec("INSERT INTO users (username, email, password_hash, role) VALUES ('demo', 'demo@dnsly.local', '$password_hash', 'user')");
$demo_user_id = $db->lastInsertId();

// Seed Domains for Demo User
$domains = ['example.com', 'myproject.dev', 'shantia.test'];
foreach ($domains as $d) {
    $stmt = $db->prepare("INSERT INTO domains (user_id, domain, status) VALUES (?, ?, 'active')");
    $stmt->execute([$demo_user_id, $d]);
    $domain_id = $db->lastInsertId();

    // Add some records
    $records = [
        ['A', '@', '192.168.1.10', 3600],
        ['A', 'www', '192.168.1.10', 3600],
        ['CNAME', 'blog', $d, 3600],
        ['MX', '@', 'mail.' . $d, 3600]
    ];

    foreach ($records as $r) {
        $stmt_r = $db->prepare("INSERT INTO dns_records (domain_id, type, name, value, ttl) VALUES (?, ?, ?, ?, ?)");
        $stmt_r->execute([$domain_id, $r[0], $r[1], $r[2], $r[3]]);
    }
}

// Add some activity logs
$stmt = $db->prepare("INSERT INTO activity_logs (user_id, action, description, ip_address) VALUES (?, ?, ?, ?)");
$stmt->execute([$demo_user_id, 'register', 'Account created via seeder', '127.0.0.1']);
$stmt->execute([$demo_user_id, 'login', 'User logged in', '127.0.0.1']);
$stmt->execute([$demo_user_id, 'create_domain', 'Created domain example.com', '127.0.0.1']);
$stmt->execute([$demo_user_id, 'create_record', 'Added A record to example.com', '127.0.0.1']);

echo "Database seeded successfully!\n";
echo "Demo User: demo@dnsly.local / password123\n";
echo "Admin User: admin@dnsly.local / password123\n";
