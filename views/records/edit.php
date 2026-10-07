<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center gap-4">
        <a href="<?= url('/domains/' . $domain[') ?>" class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Edit DNS Record</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <?php if (!empty($error)): ?>
            <div class="bg-red-50 text-red-700 p-3 rounded mb-6 text-sm"><?= html_escape($error) ?></div>
        <?php endif; ?>

        <form action="<?= url('/domains/' . $domain[') ?>"hidden" name="csrf_token" value="<?= csrf_token() ?>">/records/<?= $record['id'] ?>/edit" method="POST">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select id="type" name="type" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2 border bg-white">
                        <?php 
                        $types = ['A', 'AAAA', 'CNAME', 'MX', 'TXT', 'NS'];
                        $selected_type = $record['type'] ?? 'A';
                        foreach ($types as $t): 
                        ?>
                            <option value="<?= $t ?>" <?= $t === $selected_type ? 'selected' : '' ?>><?= $t ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" id="name" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2 border font-mono" placeholder="@ or www" value="<?= html_escape($record['name'] ?? '') ?>" required>
                    <p class="text-xs text-gray-500 mt-1">Use @ for root</p>
                </div>
            </div>

            <div class="mb-6">
                <label for="value" class="block text-sm font-medium text-gray-700 mb-1">Value</label>
                <input type="text" name="value" id="value" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2 border font-mono" placeholder="192.168.1.1 or example.com" value="<?= html_escape($record['value'] ?? '') ?>" required>
            </div>

            <div class="mb-8">
                <label for="ttl" class="block text-sm font-medium text-gray-700 mb-1">TTL (Seconds)</label>
                <select id="ttl" name="ttl" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2 border bg-white">
                    <?php 
                    $ttls = [300, 600, 1800, 3600, 86400];
                    $selected_ttl = $record['ttl'] ?? 3600;
                    foreach ($ttls as $t): 
                    ?>
                        <option value="<?= $t ?>" <?= $t == $selected_ttl ? 'selected' : '' ?>><?= $t ?> (<?= $t === 3600 ? '1 Hour' : ($t === 86400 ? '1 Day' : $t.'s') ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="flex justify-end gap-3">
                <a href="<?= url('/domains/' . $domain[') ?>" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Record
                </button>
            </div>
        </form>
    </div>
</div>
