<?php

if (!function_exists('jdd')) {
    function jdd(mixed $vars): never
    {
        if (!\in_array(\PHP_SAPI, ['cli', 'phpdbg', 'embed'], true) && !headers_sent()) {
            header('HTTP/1.1 500 Internal Server Error');
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($vars, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n";
        exit(1);
    }
}
