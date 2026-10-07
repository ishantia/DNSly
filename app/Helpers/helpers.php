<?php

function env($key, $default = null) {
    if (array_key_exists($key, $_ENV)) {
        return $_ENV[$key];
    }
    return $default;
}

function url($path) {
    $path = ltrim($path, '/');
    $baseUrl = rtrim(env('APP_URL', 'http://localhost'), '/');
    return $baseUrl . '/' . $path;
}

function view($name, $data = []) {
    extract($data);
    
    if (strpos($name, 'layouts.') === 0) {
        $path = __DIR__ . '/../../views/' . str_replace('.', '/', $name) . '.php';
        if (file_exists($path)) {
            require $path;
        }
        return;
    }
    
    $data['content'] = $name;
    extract($data);
    require __DIR__ . '/../../views/layouts/app.php';
}

function redirect($url) {
    // If it's an absolute path, prepend the base URL
    if (strpos($url, '/') === 0) {
        $url = url($url);
    }
    header("Location: {$url}");
    exit;
}

function html_escape($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die("CSRF token validation failed.");
        }
    }
}

