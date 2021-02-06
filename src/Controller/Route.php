<?php

namespace Controller;

use Attribute;
use Twig;


#[Attribute(Attribute::TARGET_METHOD)]
class Route
{
    public function __construct(
        public string $path,
        public string $name,
    ) {
        error_log("Route instanciated {$path},{$name}");
    }
}
