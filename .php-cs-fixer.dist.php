<?php

use PhpCsFixer\Config;

return (new Config())
    ->setRiskyAllowed(true)
    ->setRules([
        'global_namespace_import' => ['import_classes' => true, 'import_constants' => true, 'import_functions' => true],
        'native_function_invocation' => true,
    ])
    ->setFinder(PhpCsFixer\Finder::create()->in(__DIR__));
