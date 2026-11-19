<?php
declare(strict_types=1);

namespace App\Core;

class View
{
    /**
     * Render $view with $data, then wrap the result in $layout.
     */
    public static function render(string $view, array $data = [], string $layout = 'layouts/main'): void
    {
        extract($data, EXTR_SKIP);

        ob_start();
        require VIEWS_PATH . '/' . $view . '.php';
        $content = ob_get_clean();

        require VIEWS_PATH . '/' . $layout . '.php';
    }
}
