<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= html_escape($title ?? env('APP_NAME', 'DNSly')) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
    </style>
</head>
<body class="text-gray-900 antialiased h-screen flex flex-col">
    
    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="/" class="text-2xl font-bold text-indigo-600">DNSly</a>
                    </div>
                </div>
                <div class="flex items-center">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="/dashboard" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                        <a href="/domains" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Domains</a>
                        <a href="/activity" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Activity</a>
                        <a href="/settings" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Settings</a>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <a href="/admin" class="text-indigo-600 hover:text-indigo-800 px-3 py-2 rounded-md text-sm font-medium">Admin</a>
                        <?php endif; ?>
                        <div class="ml-4 relative flex items-center gap-4">
                            <span class="text-sm text-gray-700 font-medium"><?= html_escape($_SESSION['username']) ?></span>
                            <a href="/logout" class="text-gray-500 hover:text-gray-900 text-sm font-medium">Logout</a>
                        </div>
                    <?php else: ?>
                        <a href="/login" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">Login</a>
                        <a href="/register" class="ml-4 bg-indigo-600 text-white hover:bg-indigo-700 px-3 py-2 rounded-md text-sm font-medium">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow max-w-7xl w-full mx-auto py-6 sm:px-6 lg:px-8">
        <?php 
            if (isset($content)) {
                require __DIR__ . '/../' . str_replace('.', '/', $content) . '.php'; 
            } else {
                echo '<div class="text-center mt-20"><h1 class="text-4xl font-bold text-gray-900">Simple DNS management, without the complexity.</h1></div>';
            }
        ?>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-sm text-gray-500">&copy; <?= date('Y') ?> DNSly Showcase. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
