<?php

namespace Controller;

use Twig;

class Controller
{
    protected function render(string $template, array $data = []): string
    {
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . "/../../templates");
        $twig = new \Twig\Environment($loader, [
            'cache' => __DIR__ . "/../../var/cache_template",
        ]);

        return $twig->render($template, $data);
    }
}
