<?php

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        'native_function_invocation' => ['include' => ['@internal']],
        'no_useless_else' => true,
        'no_superfluous_elseif' => true,
        'simplified_if_return' => true,
        'static_lambda' => true,
        'lambda_not_used_import' => true,
        'function_declaration' => true,
        'no_unneeded_control_parentheses' => true,
        'yoda_style' => ['equal' => false, 'identical' => false, 'less_and_greater' => false],
        'php_unit_construct' => true,
        'php_unit_dedicate_assert' => true,
        'php_unit_dedicate_assert_internal_type' => true,
    ])
    ->setFinder(PhpCsFixer\Finder::create()->in(__DIR__));
