<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$files = [$root . '/.php-cs-fixer.dist.php', $root . '/artisan'];

foreach (['app', 'bin', 'bootstrap', 'config', 'database/migrations', 'database/seeders', 'public', 'routes', 'tests'] as $directory) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root . '/' . $directory));
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php' && !str_contains($file->getPathname(), '/bootstrap/cache/')) {
            $files[] = $file->getPathname();
        }
    }
}

sort($files);
foreach ($files as $file) {
    passthru(escapeshellarg(PHP_BINARY) . ' -l ' . escapeshellarg($file), $status);
    if ($status !== 0) {
        exit($status);
    }
}
