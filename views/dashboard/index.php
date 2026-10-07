<div>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Welcome back, <?= html_escape($_SESSION['username']) ?></h1>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Domains</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100"><?= html_escape($stats['total_domains']) ?></p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Active Domains</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100"><?= html_escape($stats['active_domains']) ?></p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total DNS Records</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100"><?= html_escape($stats['total_records']) ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Domains -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Recent Domains</h3>
                <a href="<?= url('/domains') ?>" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">View all</a>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                <?php if (empty($recent_domains)): ?>
                    <div class="p-6 text-center text-gray-500 dark:text-gray-400 text-sm">No domains found. <a href="<?= url('/domains/create') ?>" class="text-indigo-600">Create one</a>.</div>
                <?php else: ?>
                    <?php foreach ($recent_domains as $domain): ?>
                        <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100"><?= html_escape($domain['domain']) ?></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Created <?= html_escape(date('M j, Y', strtotime($domain['created_at']))) ?></p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $domain['status'] == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800 dark:text-gray-200' ?>">
                                <?= html_escape(ucfirst($domain['status'])) ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Recent Activity</h3>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                <?php if (empty($recent_activity)): ?>
                    <div class="p-6 text-center text-gray-500 dark:text-gray-400 text-sm">No recent activity.</div>
                <?php else: ?>
                    <?php foreach ($recent_activity as $log): ?>
                        <div class="px-6 py-4">
                            <p class="text-sm text-gray-900 dark:text-gray-100"><?= html_escape($log['description']) ?></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"><?= html_escape(date('M j, Y H:i', strtotime($log['created_at']))) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
