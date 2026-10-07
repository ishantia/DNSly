<?php

namespace App\Services;

class Router {
    private array $routes = [];

    public function get($uri, $action) {
        $this->addRoute('GET', $uri, $action);
    }

    public function post($uri, $action) {
        $this->addRoute('POST', $uri, $action);
    }

    private function addRoute($method, $uri, $action) {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'action' => $action
        ];
    }

    public function dispatch($requestUri, $requestMethod) {
        // Strip query string
        if (false !== $pos = strpos($requestUri, '?')) {
            $requestUri = substr($requestUri, 0, $pos);
        }

        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod) {
                // simple route matching (no params for now, we can add later if needed)
                // convert /user/{id} to regex
                $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_-]+)', $route['uri']);
                $pattern = "#^" . $pattern . "$#";
                
                if (preg_match($pattern, $requestUri, $matches)) {
                    array_shift($matches); // remove full match
                    
                    if (is_callable($route['action'])) {
                        return call_user_func_array($route['action'], $matches);
                    }
                    
                    if (is_array($route['action'])) {
                        $controller = new $route['action'][0]();
                        $method = $route['action'][1];
                        return call_user_func_array([$controller, $method], $matches);
                    }
                }
            }
        }

        // 404
        http_response_code(404);
        echo "404 Not Found";
    }
}
