<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center gap-4">
        <a href="/domains" class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Add New Domain</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <?php if (!empty($error)): ?>
            <div class="bg-red-50 text-red-700 p-3 rounded mb-6 text-sm"><?= html_escape($error) ?></div>
        <?php endif; ?>

        <form action="/domains/create" method="POST">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <div class="mb-6">
                <label for="domain" class="block text-sm font-medium text-gray-700 mb-1">Domain Name</label>
                <div class="relative rounded-md shadow-sm">
                    <input type="text" name="domain" id="domain" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md px-4 py-2 border" placeholder="example.com" value="<?= html_escape($domain ?? '') ?>" required>
                </div>
                <p class="mt-2 text-sm text-gray-500">Enter the bare domain name without www or http://</p>
            </div>

            <div class="flex justify-end gap-3">
                <a href="/domains" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Add Domain
                </button>
            </div>
        </form>
    </div>
</div>
