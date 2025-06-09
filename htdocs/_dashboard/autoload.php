<?php

spl_autoload_register(function ($class) {
    // Only handle classes from the "Dashboard" namespace
    if (str_starts_with($class, 'Dashboard\\')) {
        $baseDir = __DIR__ . '/'; // _olddashboard folder
        $relativeClass = substr($class, strlen('Dashboard\\'));
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

        if (file_exists($file)) {
            require_once $file;
        }
    }
});
