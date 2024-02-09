<?php

use PhpCsFixer\Config;

return (new Config())
    ->setRiskyAllowed(true)
    ->setRules([
        'native_function_invocation' => true,
    ])
    ->setFinder(PhpCsFixer\Finder::create()->in(__DIR__));
