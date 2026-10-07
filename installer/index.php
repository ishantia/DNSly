<?php
session_start();

$step = $_GET['step'] ?? 1;
$error = '';
$success = '';

$envFile = __DIR__ . '/../.env';
$schemaFile = __DIR__ . '/../database/schema.sql';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($step == 1) {
        $dbHost = $_POST['db_host'] ?? '127.0.0.1';
        $dbPort = $_POST['db_port'] ?? '3306';
        $dbName = $_POST['db_name'] ?? '';
        $dbUser = $_POST['db_user'] ?? '';
        $dbPass = $_POST['db_pass'] ?? '';
        $appUrl = $_POST['app_url'] ?? 'http://localhost';

        try {
            // Connect to MySQL without specifying the database first
            $dsn = "mysql:host=$dbHost;port=$dbPort;charset=utf8mb4";
            $pdo = new PDO($dsn, $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

            // Auto-create the database if it doesn't exist
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            
            // Now select the database
            $pdo->exec("USE `$dbName`");

            // Connection successful, write .env
            $envContent = "APP_NAME=DNSly\n";
            $envContent .= "APP_ENV=production\n";
            $envContent .= "APP_URL={$appUrl}\n";
            $envContent .= "APP_DEBUG=false\n\n";
            $envContent .= "DB_HOST={$dbHost}\n";
            $envContent .= "DB_PORT={$dbPort}\n";
            $envContent .= "DB_DATABASE={$dbName}\n";
            $envContent .= "DB_USERNAME={$dbUser}\n";
            $envContent .= "DB_PASSWORD={$dbPass}\n";

            file_put_contents($envFile, $envContent);

            // Import Schema
            $sql = file_get_contents($schemaFile);
            $pdo->exec($sql);

            // Seed Data
            $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
            $pdo->exec("TRUNCATE TABLE users;");
            $pdo->exec("TRUNCATE TABLE domains;");
            $pdo->exec("TRUNCATE TABLE dns_records;");
            $pdo->exec("TRUNCATE TABLE activity_logs;");
            $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");

            $password_hash = password_hash('password123', PASSWORD_DEFAULT);

            // Seed Admin
            $pdo->exec("INSERT INTO users (username, email, password_hash, role) VALUES ('admin', 'admin@dnsly.local', '$password_hash', 'admin')");

            // Seed Demo User
            $pdo->exec("INSERT INTO users (username, email, password_hash, role) VALUES ('demo', 'demo@dnsly.local', '$password_hash', 'user')");
            $demo_user_id = $pdo->lastInsertId();

            // Seed Domains
            $domains = ['example.com', 'myproject.dev', 'shantia.test'];
            foreach ($domains as $d) {
                $stmt = $pdo->prepare("INSERT INTO domains (user_id, domain, status) VALUES (?, ?, 'active')");
                $stmt->execute([$demo_user_id, $d]);
                $domain_id = $pdo->lastInsertId();

                $records = [
                    ['A', '@', '192.168.1.10', 3600],
                    ['A', 'www', '192.168.1.10', 3600],
                    ['CNAME', 'blog', $d, 3600],
                    ['MX', '@', 'mail.' . $d, 3600]
                ];

                foreach ($records as $r) {
                    $stmt_r = $pdo->prepare("INSERT INTO dns_records (domain_id, type, name, value, ttl) VALUES (?, ?, ?, ?, ?)");
                    $stmt_r->execute([$domain_id, $r[0], $r[1], $r[2], $r[3]]);
                }
            }

            // Seed Activity
            $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, description, ip_address) VALUES (?, ?, ?, ?)");
            $stmt->execute([$demo_user_id, 'register', 'Account created via installer', '127.0.0.1']);

            header("Location: ?step=2");
            exit;

        } catch (PDOException $e) {
            $error = "Database connection failed: " . $e->getMessage();
        } catch (Exception $e) {
            $error = "An error occurred: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DNSly Installer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
    
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="text-center text-3xl font-extrabold text-gray-900">DNSly Installer</h2>
        <p class="mt-2 text-center text-sm text-gray-600">Setup your DNS simulation platform</p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 border border-gray-100">
            
            <?php if ($step == 1): ?>
                
                <?php if ($error): ?>
                    <div class="bg-red-50 text-red-700 p-4 rounded mb-6 text-sm">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form class="space-y-4" action="?step=1" method="POST">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">App URL</label>
                        <?php
                        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                        $host = $_SERVER['HTTP_HOST'];
                        $scriptName = dirname(dirname($_SERVER['SCRIPT_NAME'])); // e.g. /DNSly
                        $scriptName = str_replace('\\', '/', $scriptName);
                        if ($scriptName === '/') $scriptName = '';
                        $defaultAppUrl = $protocol . $host . $scriptName;
                        ?>
                        <input type="url" name="app_url" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="<?= htmlspecialchars($defaultAppUrl) ?>" required>
                    </div>

                    <div class="pt-4 border-t border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Database Credentials</h3>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Database Host</label>
                            <input type="text" name="db_host" value="127.0.0.1" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Port</label>
                            <input type="number" name="db_port" value="3306" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Database Name</label>
                        <input type="text" name="db_name" placeholder="dnsly" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Database User</label>
                        <input type="text" name="db_user" placeholder="root" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Database Password</label>
                        <input type="password" name="db_pass" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Install and Configure Database
                        </button>
                    </div>
                </form>

            <?php elseif ($step == 2): ?>
                
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Installation Successful!</h3>
                    
                    <div class="mt-4 text-sm text-gray-600 space-y-3 bg-gray-50 p-4 rounded text-left border border-gray-200">
                        <p><strong>Database seeded with demo data.</strong></p>
                        <p>Admin Login: <code class="bg-gray-200 px-1 rounded">admin@dnsly.local</code> / <code class="bg-gray-200 px-1 rounded">password123</code></p>
                        <p>Demo Login: <code class="bg-gray-200 px-1 rounded">demo@dnsly.local</code> / <code class="bg-gray-200 px-1 rounded">password123</code></p>
                    </div>

                    <div class="mt-6 p-4 bg-red-50 text-red-700 rounded text-sm text-left border border-red-100">
                        <strong>Security Warning:</strong><br>
                        Please delete the <code>/installer</code> directory from your server immediately to prevent unauthorized reconfiguration.
                    </div>

                    <div class="mt-6">
                        <a href="<?= htmlspecialchars($appUrl ?? '/') ?>" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Go to App
                        </a>
                    </div>
                </div>

            <?php endif; ?>

        </div>
    </div>
</body>
</html>
