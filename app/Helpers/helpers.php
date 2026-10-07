<?php

function env($key, $default = null) {
    if (array_key_exists($key, $_ENV)) {
        return $_ENV[$key];
    }
    return $default;
}

function view($name, $data = []) {
    extract($data);
    $path = __DIR__ . '/../../views/' . str_replace('.', '/', $name) . '.php';
    if (file_exists($path)) {
        require $path;
    } else {
        echo "View {$name} not found.";
    }
}

function redirect($url) {
    header("Location: {$url}");
    exit;
}

function html_escape($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}
