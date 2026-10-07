<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= html_escape($title ?? env('APP_NAME', 'DNSly')) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/htmx.org@1.9.10"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
        
        /* HTMX Loading indicator */
        .htmx-indicator { opacity: 0; transition: opacity 200ms ease-in; }
        .htmx-request .htmx-indicator { opacity: 1 }
        .htmx-request.htmx-indicator { opacity: 1 }
        
        /* Top progress bar */
        #progress-bar {
            position: fixed; top: 0; left: 0; height: 3px; background-color: #4f46e5;
            width: 0%; transition: width 0.2s, opacity 0.4s; z-index: 9999; opacity: 0;
        }
        body.htmx-request #progress-bar { width: 50%; opacity: 1; transition: width 2s cubic-bezier(0.1, 0.5, 0.5, 1); }
    </style>
    <script>
        document.addEventListener('htmx:beforeRequest', () => {
            document.getElementById('progress-bar').style.width = '10%';
            document.getElementById('progress-bar').style.opacity = '1';
        });
        document.addEventListener('htmx:afterOnLoad', () => {
            document.getElementById('progress-bar').style.width = '100%';
            setTimeout(() => { document.getElementById('progress-bar').style.opacity = '0'; }, 300);
            setTimeout(() => { document.getElementById('progress-bar').style.width = '0%'; }, 700);
        });
    </script>
</head>
<body class="text-gray-900 antialiased min-h-screen flex flex-col" hx-boost="true" hx-indicator="#progress-bar">
    <div id="progress-bar"></div>

    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50" x-data="{ mobileMenuOpen: false, userMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="<?= url('/') ?>" class="text-2xl font-bold text-indigo-600 tracking-tight flex items-center gap-2">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                            DNSly
                        </a>
                    </div>
                    <!-- Desktop Nav Links -->
                    <div class="hidden sm:ml-8 sm:flex sm:space-x-4 sm:items-center">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="<?= url('/dashboard') ?>" class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">Dashboard</a>
                            <a href="<?= url('/domains') ?>" class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">Domains</a>
                            <a href="<?= url('/activity') ?>" class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">Activity</a>
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <a href="<?= url('/admin') ?>" class="text-indigo-600 hover:text-indigo-800 px-3 py-2 rounded-md text-sm font-medium transition-colors bg-indigo-50">Admin Panel</a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Desktop User Menu -->
                <div class="hidden sm:flex sm:items-center">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="relative ml-3">
                            <div>
                                <button @click="userMenuOpen = !userMenuOpen" @click.away="userMenuOpen = false" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-indigo-300 transition items-center gap-2 bg-gray-50 px-3 py-1.5 border-gray-200 hover:bg-gray-100">
                                    <div class="h-6 w-6 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-xs uppercase">
                                        <?= substr(html_escape($_SESSION['username']), 0, 1) ?>
                                    </div>
                                    <span class="font-medium text-gray-700"><?= html_escape($_SESSION['username']) ?></span>
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                            </div>
                            <div x-show="userMenuOpen" x-transition.opacity.duration.200ms class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50" style="display: none;">
                                <a href="<?= url('/settings') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                                <a href="<?= url('/logout') ?>" hx-boost="false" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">Sign out</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?= url('/login') ?>" class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">Sign in</a>
                        <a href="<?= url('/register') ?>" class="ml-4 bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded-md text-sm font-medium shadow-sm transition-all hover:shadow-md">Get Started</a>
                    <?php endif; ?>
                </div>

                <!-- Mobile menu button -->
                <div class="flex items-center sm:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                        <svg class="h-6 w-6" x-show="!mobileMenuOpen" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                        <svg class="h-6 w-6" x-show="mobileMenuOpen" style="display: none;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="sm:hidden border-t border-gray-200 bg-white" x-show="mobileMenuOpen" x-collapse style="display: none;">
            <div class="pt-2 pb-3 space-y-1">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?= url('/dashboard') ?>" @click="mobileMenuOpen = false" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-indigo-800 hover:bg-indigo-50 hover:border-indigo-500">Dashboard</a>
                    <a href="<?= url('/domains') ?>" @click="mobileMenuOpen = false" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-indigo-800 hover:bg-indigo-50 hover:border-indigo-500">Domains</a>
                    <a href="<?= url('/activity') ?>" @click="mobileMenuOpen = false" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-indigo-800 hover:bg-indigo-50 hover:border-indigo-500">Activity</a>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <a href="<?= url('/admin') ?>" @click="mobileMenuOpen = false" class="block pl-3 pr-4 py-2 border-l-4 border-indigo-500 text-base font-medium text-indigo-700 bg-indigo-50">Admin Panel</a>
                    <?php endif; ?>
                    <div class="border-t border-gray-200 mt-4 pt-4 pb-2">
                        <div class="flex items-center px-4">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-lg uppercase">
                                <?= substr(html_escape($_SESSION['username']), 0, 1) ?>
                            </div>
                            <div class="ml-3">
                                <div class="text-base font-medium text-gray-800"><?= html_escape($_SESSION['username']) ?></div>
                            </div>
                        </div>
                        <div class="mt-3 space-y-1">
                            <a href="<?= url('/settings') ?>" @click="mobileMenuOpen = false" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Settings</a>
                            <a href="<?= url('/logout') ?>" hx-boost="false" class="block px-4 py-2 text-base font-medium text-red-500 hover:text-red-800 hover:bg-red-50">Sign out</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?= url('/login') ?>" @click="mobileMenuOpen = false" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-indigo-800 hover:bg-indigo-50 hover:border-indigo-500">Sign in</a>
                    <a href="<?= url('/register') ?>" @click="mobileMenuOpen = false" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 hover:border-indigo-500">Create an account</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow w-full max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 transition-opacity duration-200" id="main-content">
        <?php 
            if (isset($content)) {
                require __DIR__ . '/../' . str_replace('.', '/', $content) . '.php'; 
            } else {
                echo '<div class="text-center mt-20"><div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-indigo-100 text-indigo-600 mb-6"><svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg></div><h1 class="text-5xl font-extrabold text-gray-900 tracking-tight mb-4">DNSly</h1><p class="text-xl text-gray-500 mb-8">Modern DNS management, without the complexity.</p><a href="'.url('/register').'" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 transition-all hover:scale-105">Get Started for Free</a></div>';
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
