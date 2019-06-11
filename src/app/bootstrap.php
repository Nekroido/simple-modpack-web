<?php

namespace SimpleModpack;

spl_autoload_register(function ($class) {
    $filename = 'app' . str_replace(__NAMESPACE__, '', str_replace('\\', DIRECTORY_SEPARATOR, $class)) . '.php';

    if (file_exists($filename)) {
        require_once $filename;
        return true;
    }
    return false;
});
