<div>
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="/domains" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900"><?= html_escape($domain['domain']) ?></h1>
            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium <?= $domain['status'] == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                <?= html_escape(ucfirst($domain['status'])) ?>
            </span>
        </div>
        <form action="/domains/<?= $domain['id'] ?>/delete" method="POST" onsubmit="return confirm('Are you sure you want to delete this domain? This cannot be undone.');">
            <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-red-200 text-xs font-medium rounded text-red-600 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                Delete Domain
            </button>
        </form>
    </div>

    <!-- Nameservers Box -->
    <div class="bg-indigo-50 rounded-xl border border-indigo-100 p-6 mb-8">
        <h3 class="text-indigo-900 font-semibold mb-3">Nameservers</h3>
        <p class="text-indigo-700 text-sm mb-4">To activate this domain, point your nameservers to:</p>
        <div class="bg-white rounded border border-indigo-200 p-3 flex flex-col gap-2 font-mono text-sm text-indigo-900">
            <div>ns1.dnsly.local</div>
            <div>ns2.dnsly.local</div>
        </div>
        <p class="text-xs text-indigo-500 mt-3 italic">* This is a simulation platform. DNS will not actually propagate.</p>
    </div>

    <!-- DNS Records Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-lg font-medium text-gray-900">DNS Records</h2>
            <a href="/domains/<?= $domain['id'] ?>/records/create" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Add Record
            </a>
        </div>
        
        <?php if (empty($records)): ?>
            <div class="p-8 text-center text-gray-500 text-sm">
                No DNS records found for this domain.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">TTL</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 font-mono text-sm">
                        <?php foreach ($records as $record): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-700"><?= html_escape($record['type']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900"><?= html_escape($record['name']) ?></td>
                                <td class="px-6 py-4 text-gray-500 max-w-xs truncate"><?= html_escape($record['value']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500"><?= html_escape($record['ttl']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium font-sans">
                                    <a href="/domains/<?= $domain['id'] ?>/records/<?= $record['id'] ?>/edit" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                    <form action="/domains/<?= $domain['id'] ?>/records/<?= $record['id'] ?>/delete" method="POST" class="inline" onsubmit="return confirm('Delete this record?');">
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
