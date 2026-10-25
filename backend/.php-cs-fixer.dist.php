<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude(['vendor', 'storage', 'bootstrap/cache'])
    ->append([__FILE__, __DIR__ . '/artisan']);

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(false)
    ->setRules(['@PSR12' => true])
    ->setFinder($finder);
