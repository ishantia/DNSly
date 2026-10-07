<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
        <p class="text-gray-500 text-sm mt-1">Platform overview and management.</p>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-sm font-medium text-gray-500 mb-1">Total Users</h3>
        <p class="text-3xl font-bold text-gray-900"><?= html_escape($stats['total_users']) ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-sm font-medium text-gray-500 mb-1">Total Domains</h3>
        <p class="text-3xl font-bold text-gray-900"><?= html_escape($stats['total_domains']) ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-sm font-medium text-gray-500 mb-1">DNS Records</h3>
        <p class="text-3xl font-bold text-gray-900"><?= html_escape($stats['total_records']) ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-sm font-medium text-gray-500 mb-1">24h Activity</h3>
        <p class="text-3xl font-bold text-gray-900"><?= html_escape($stats['recent_activity']) ?></p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Recent Users -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-medium text-gray-900">Recent Registrations</h3>
        </div>
        <div class="divide-y divide-gray-100">
            <?php foreach ($recent_users as $u): ?>
                <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
                    <div>
                        <p class="text-sm font-medium text-gray-900"><?= html_escape($u['username']) ?></p>
                        <p class="text-xs text-gray-500"><?= html_escape($u['email']) ?></p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $u['role'] == 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' ?>">
                        <?= html_escape(ucfirst($u['role'])) ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Platform Activity -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-medium text-gray-900">Platform Activity</h3>
        </div>
        <div class="divide-y divide-gray-100">
            <?php foreach ($recent_activity as $log): ?>
                <div class="px-6 py-4">
                    <div class="flex justify-between items-start">
                        <p class="text-sm font-medium text-gray-900">
                            <?= html_escape($log['username'] ?? 'System') ?>
                        </p>
                        <span class="text-xs text-gray-500"><?= html_escape(date('M j H:i', strtotime($log['created_at']))) ?></span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1"><?= html_escape($log['description']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
