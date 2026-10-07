<?php
$dir = new RecursiveDirectoryIterator(__DIR__ . '/views');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/.*\.php$/', RegexIterator::GET_MATCH);
foreach ($files as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    $content = preg_replace_callback('/<\?= url\(\'(.*?)\'([^)]*)\) \?>/', function($m) {
        $str = $m[1];
        if (strpos($str, '<?=') !== false) {
            $str = str_replace('<?= ', "' . ", $str);
            $str = str_replace(' ?>', " . '", $str);
            $str = str_replace(" . ''", "", $str);
            return "<?= url('" . $str . "') ?>";
        }
        return $m[0];
    }, $content);
    file_put_contents($path, $content);
}
echo "Fixed.\n";
