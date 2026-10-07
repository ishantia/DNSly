<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/views');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/.*\.php$/', RegexIterator::GET_MATCH);

foreach ($files as $file) {
    $path = $file[0];
    if (strpos($path, 'layouts') !== false) {
        continue;
    }
    
    $content = file_get_contents($path);
    
    // Wrap simple strings correctly. E.g. href="/domains"
    $content = preg_replace('/href="(\/[^"<]*?)"/', 'href="<?= url(\'$1\') ?>"', $content);
    $content = preg_replace('/action="(\/[^"<]*?)"/', 'action="<?= url(\'$1\') ?>"', $content);

    // Now fix the ones that incorrectly wrapped PHP tags inside the string
    // E.g. href="<?= url('/domains/<?= $domain['id'] ?>') ?>"
    // We want to turn this into: href="<?= url('/domains/' . $domain['id']) ?>"
    
    // A much safer way: just explicitly replace the exact strings we know exist!
    $replacements = [
        "<?= url('/domains/<?= \$domain['id'] ?>') ?>" => "<?= url('/domains/' . \$domain['id']) ?>",
        "<?= url('/domains/<?= \$domain['id'] ?>/delete') ?>" => "<?= url('/domains/' . \$domain['id'] . '/delete') ?>",
        "<?= url('/domains/<?= \$domain['id'] ?>/records/create') ?>" => "<?= url('/domains/' . \$domain['id'] . '/records/create') ?>",
        "<?= url('/domains/<?= \$domain['id'] ?>/records/<?= \$record['id'] ?>/edit') ?>" => "<?= url('/domains/' . \$domain['id'] . '/records/' . \$record['id'] . '/edit') ?>",
        "<?= url('/domains/<?= \$domain['id'] ?>/records/<?= \$record['id'] ?>/delete') ?>" => "<?= url('/domains/' . \$domain['id'] . '/records/' . \$record['id'] . '/delete') ?>"
    ];
    
    foreach ($replacements as $search => $replace) {
        $content = str_replace($search, $replace, $content);
    }
    
    // Fix CSRF
    // If a form has method="POST" but lacks csrf_token, add it.
    $content = preg_replace('/(<form[^>]*method="POST"[^>]*>)\s*(?!<input type="hidden" name="csrf_token")/', "$1\n            <input type=\"hidden\" name=\"csrf_token\" value=\"<?= csrf_token() ?>\">\n", $content);
    
    file_put_contents($path, $content);
}
echo "Perfectly wrapped.\n";
