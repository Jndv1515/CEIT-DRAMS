<?php
declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    /**
     * Render a view inside the main layout.
     */
    protected function view(string $view, array $data = [], string $layout = 'layouts/main'): void
    {
        View::render($view, $data, $layout);
    }

    /**
     * Redirect to an application URL.
     */
    protected function redirect(string $path): void
    {
        header('Location: ' . url($path), true, 302);
        exit;
    }
}
