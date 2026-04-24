<?php
// ============================================================
// Base Controller — Aurora Restaurant
// ============================================================

abstract class Controller
{

    protected function view(string $view, array $data = []): void
    {
        // IMPORTANT: compute $viewFile BEFORE extract() can overwrite $view param
        $viewFile = BASE_PATH . "/views/{$view}.php";
        if (!file_exists($viewFile)) {
            http_response_code(500);
            die("View not found: {$view}");
        }
        extract($data); // may overwrite local $view → that's fine, layout uses it
        require $viewFile;
    }

    protected function json(mixed $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    protected function redirect(string $path): void
    {
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            header('Location: ' . $path);
        } else {
            header('Location: ' . BASE_URL . $path);
        }
        exit;
    }

    protected function input(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function isAjax(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
            || isset($_SERVER['HTTP_ACCEPT']) 
            && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false
            || isset($_SERVER['CONTENT_TYPE']) 
            && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false;
    }
}
