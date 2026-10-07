<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
    <p class="text-gray-500 text-sm mt-1">Manage your account settings and preferences.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    
    <!-- Profile Info -->
    <div class="md:col-span-1">
        <h3 class="text-lg font-medium text-gray-900">Profile</h3>
        <p class="text-sm text-gray-500 mt-1">Your basic account information.</p>
    </div>
    <div class="md:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Username</label>
                <p class="mt-1 text-gray-900 bg-gray-50 p-2 rounded border border-gray-200"><?= html_escape($user['username']) ?></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <p class="mt-1 text-gray-900 bg-gray-50 p-2 rounded border border-gray-200"><?= html_escape($user['email']) ?></p>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700">Member Since</label>
                <p class="mt-1 text-sm text-gray-500"><?= html_escape(date('F j, Y', strtotime($user['created_at']))) ?></p>
            </div>
        </div>
    </div>

    <div class="col-span-1 md:col-span-3 border-t border-gray-200 my-4"></div>

    <!-- Theme Preference -->
    <div class="md:col-span-1">
        <h3 class="text-lg font-medium text-gray-900">Appearance</h3>
        <p class="text-sm text-gray-500 mt-1">Customize how DNSly looks.</p>
    </div>
    <div class="md:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form action="/settings/theme" method="POST">
                <label for="theme" class="block text-sm font-medium text-gray-700 mb-2">Theme</label>
                <select id="theme" name="theme" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2 border bg-white mb-4">
                    <option value="system" <?= $user['theme'] === 'system' ? 'selected' : '' ?>>System (Default)</option>
                    <option value="light" <?= $user['theme'] === 'light' ? 'selected' : '' ?>>Light</option>
                    <option value="dark" <?= $user['theme'] === 'dark' ? 'selected' : '' ?>>Dark</option>
                </select>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    Save Preference
                </button>
            </form>
        </div>
    </div>

    <div class="col-span-1 md:col-span-3 border-t border-gray-200 my-4"></div>

    <!-- Password Settings -->
    <div class="md:col-span-1">
        <h3 class="text-lg font-medium text-gray-900">Security</h3>
        <p class="text-sm text-gray-500 mt-1">Update your password to keep your account secure.</p>
    </div>
    <div class="md:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <?php if (!empty($password_error)): ?>
                <div class="bg-red-50 text-red-700 p-3 rounded mb-4 text-sm"><?= html_escape($password_error) ?></div>
            <?php endif; ?>
            <?php if (!empty($password_success)): ?>
                <div class="bg-green-50 text-green-700 p-3 rounded mb-4 text-sm"><?= html_escape($password_success) ?></div>
            <?php endif; ?>

            <form action="/settings/password" method="POST">
                <div class="mb-4">
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                    <input type="password" name="current_password" id="current_password" required class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2 border">
                </div>
                <div class="mb-4">
                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <input type="password" name="new_password" id="new_password" required class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2 border">
                </div>
                <div class="mb-6">
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" required class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2 border">
                </div>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    Update Password
                </button>
            </form>
        </div>
    </div>
</div>
