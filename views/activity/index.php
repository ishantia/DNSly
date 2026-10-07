<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Activity Log</h1>
    <p class="text-gray-500 text-sm mt-1">Review your recent actions and account changes.</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <?php if (empty($logs)): ?>
        <div class="p-12 text-center text-gray-500">
            No activity found.
        </div>
    <?php else: ?>
        <div class="divide-y divide-gray-100">
            <?php foreach ($logs as $log): ?>
                <div class="p-6 hover:bg-gray-50 transition-colors flex items-start gap-4">
                    <div class="bg-indigo-100 p-2 rounded-full mt-1">
                        <svg class="h-4 w-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900"><?= html_escape($log['description']) ?></p>
                        <div class="mt-1 flex items-center gap-2 text-xs text-gray-500">
                            <span><?= html_escape(date('M j, Y H:i:s', strtotime($log['created_at']))) ?></span>
                            <span>&bull;</span>
                            <span class="font-mono text-gray-400"><?= html_escape($log['ip_address'] ?? 'Unknown IP') ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
